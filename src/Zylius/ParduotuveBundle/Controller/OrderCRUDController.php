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
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Zylius\ParduotuveBundle\Entity\LogItem;
use Zylius\ParduotuveBundle\Entity\ProductOrder;
use Zylius\ParduotuveBundle\Entity\ProductOrderRepository;
use Zylius\ParduotuveBundle\Entity\User;
use Zylius\ParduotuveBundle\Form\ProductOrderType;
use Zylius\ParduotuveBundle\Form\ProductType;
use Zylius\ParduotuveBundle\Form\UserType;

class OrderCRUDController extends Controller
{
    public function readAction()
    {
        $orders = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:ProductOrder')->findAll();

        return $this->render(
            'ZyliusParduotuveBundle:Order:list.html.twig',
            ['orders' => $orders, 'user' => $this->getUser()]
        );
    }

    public function updateAction(Request $request, $id)
    {
        /** @var ProductOrder $productOrder */
        $productOrder = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:ProductOrder')->find($id);
        $form = $this->createForm(new ProductOrderType(), $productOrder);

        if (!in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles()) && $productOrder->getConfirmer() !== $this->getUser()) {
            throw new AuthenticationException();
        }

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            $logItem = new LogItem();
            $logItem->setUser($this->getUser());
            $logItem->setTime(new \DateTime());
            $logItem->setValue('Edited order #' . $productOrder->getId() . '.');

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($logItem);
            $em->persist($productOrder);
            $em->flush();
            $data = ['message' => 'form.success.edit'];
        } else {
            $data = ['form' => $form->createView()];
        }

        return $this->render(
            'ZyliusParduotuveBundle:Order:edit.html.twig',
            $data
        );
    }

    public function deleteAction($id)
    {
        $order = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:ProductOrder')->find($id);
        $this->getDoctrine()->getEntityManager()->remove($order);

        $logItem = new LogItem();
        $logItem->setUser($this->getUser());
        $logItem->setTime(new \DateTime());
        $logItem->setValue('Deleted order #' . $order->getId() . '.');

        $this->getDoctrine()->getEntityManager()->persist($logItem);
        $this->getDoctrine()->getEntityManager()->flush();
        return new RedirectResponse($this->generateUrl('zylius_order_homepage'));
    }

    public function confirmAction($id)
    {
        /** @var ProductOrder $order */
        $order =  $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:ProductOrder')->find($id);
        $order->setConfirmer($this->getUser());

        $logItem = new LogItem();
        $logItem->setUser($this->getUser());
        $logItem->setTime(new \DateTime());
        $logItem->setValue('Confirmed order #' . $order->getId() . '.');

        $this->getDoctrine()->getEntityManager()->persist($logItem);
        $this->getDoctrine()->getEntityManager()->flush();
        return new RedirectResponse($this->generateUrl('zylius_order_homepage'));
    }


    public function statisticsAction()
    {
        /** @var ProductOrderRepository $rep */
        $rep = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:ProductOrder');
        $statistics = $rep->getStatistics($this->getUser());

        return $this->render(
            'ZyliusParduotuveBundle:Order:statistics.html.twig',
            $statistics[0]
        );
    }
} 