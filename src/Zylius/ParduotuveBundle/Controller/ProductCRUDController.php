<?php

namespace Zylius\ParduotuveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Zylius\ParduotuveBundle\Entity\LogItem;
use Zylius\ParduotuveBundle\Entity\Product;
use Zylius\ParduotuveBundle\Entity\ProductOrder;
use Zylius\ParduotuveBundle\Form\ProductType;

class ProductCRUDController extends Controller
{
    public function readAction($page = 0)
    {
        $perPage = 10;
        $products = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:Product')->findBy([], null, $perPage, $perPage * $page);
        return $this->render(
            'ZyliusParduotuveBundle:Main:index.html.twig',
            ['products' => $products, 'page' => $page]
        );
    }

    public function readBucketAction(Request $request)
    {
        $items = json_decode($request->cookies->get('basket'));
        $products = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:Product')->findBy(['id' => $items]);
        if($request->getMethod() === 'POST') {
            $securityContext = $this->container->get('security.context');
            if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                throw new AuthenticationException();
            }
            $order = new ProductOrder();
            $order->setUser($this->getUser());
            $order->setDate(new \DateTime());
            foreach ($products as $product) {
                $order->addProduct($product);
            }

            $logItem = new LogItem();
            $logItem->setUser($this->getUser());
            $logItem->setTime(new \DateTime());
            $logItem->setValue('Created an order.');

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($logItem);
            $em->persist($order);
            $em->flush();

            $request->cookies->set('basket', null);
            $response = $this->render(
                'ZyliusParduotuveBundle:Main:order_list.html.twig',
                ['message' => 'basket.success']
            );
            $response->headers->setCookie(new Cookie('basket', null));
        } else {
            $response = $this->render(
                'ZyliusParduotuveBundle:Main:order_list.html.twig',
                ['products' => $products]
            );
        }

        return $response;
    }

    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(new ProductType(), $product);

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            $product->setUser($this->getUser());

            $logItem = new LogItem();
            $logItem->setUser($this->getUser());
            $logItem->setTime(new \DateTime());
            $logItem->setValue('Created product "' . $product->getName() . '".');

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($logItem);
            $em->persist($product);
            $em->flush();
            $data = ['message' => 'product.success'];
        } else {
            $data = ['form' => $form->createView()];
        }

        return $this->render(
            'ZyliusParduotuveBundle:Admin:add_product.html.twig',
            $data
        );
    }

    public function deleteAction(Request $request)
    {
        $indexes = array_keys($request->request->all());
        foreach ($indexes as $index) {
            $this->getDoctrine()->getEntityManager()->remove(
                $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:Product')->find($index)
            );
        }
        $this->getDoctrine()->getEntityManager()->flush();
        return new RedirectResponse($this->generateUrl('zylius_parduotuve_homepage'));
    }
}
