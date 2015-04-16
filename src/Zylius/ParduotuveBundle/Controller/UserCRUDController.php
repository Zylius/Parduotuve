<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2015-04-13
 * Time: 14:11
 */

namespace Zylius\ParduotuveBundle\Controller;


use FOS\UserBundle\Form\Type\UsernameFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Zylius\ParduotuveBundle\Entity\LogItem;
use Zylius\ParduotuveBundle\Entity\User;
use Zylius\ParduotuveBundle\Form\UserType;

class UserCRUDController extends Controller
{
    public function readAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $users = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:User')->findAll();
        if (!in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            foreach($users as $key => $user) {
                if($user->getRoles() !== ['ROLE_USER']) {
                    unset($users[$key]);
                }
            }
        }
        return $this->render(
            'ZyliusParduotuveBundle:Users:list.html.twig',
            ['users' => $users]
        );
    }

    public function updateAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:User')->find($id);
        $form = $this->createForm(new UserType($this->getUser()), $user);

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if(!$form->isValid()) {
                return $this->render(
                    'ZyliusParduotuveBundle:Users:edit.html.twig',
                    ['form' => $form->createView()]
                );
            }

            $logItem = new LogItem();
            $logItem->setUser($this->getUser());
            $logItem->setTime(new \DateTime());
            $logItem->setValue('Edited user #' . $user->getId() . '.');

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($logItem);
            $em->persist($user);
            $em->flush();
            $data = ['message' => 'form.success.edit'];
        } else {
            $data = ['form' => $form->createView()];
        }

        return $this->render(
            'ZyliusParduotuveBundle:Users:edit.html.twig',
            $data
        );
    }

    public function deleteAction($id)
    {
        $this->getDoctrine()->getEntityManager()->remove(
            $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:User')->find($id)
        );

        $logItem = new LogItem();
        $logItem->setUser($this->getUser());
        $logItem->setTime(new \DateTime());
        $logItem->setValue('Deleted User #' . $id . '.');
        $this->getDoctrine()->getEntityManager()->persist($logItem);

        $this->getDoctrine()->getEntityManager()->flush();
        return new RedirectResponse($this->generateUrl('zylius_user_homepage'));
    }
} 