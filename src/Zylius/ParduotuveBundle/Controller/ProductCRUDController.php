<?php

namespace Zylius\ParduotuveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Zylius\ParduotuveBundle\Entity\LogItem;
use Zylius\ParduotuveBundle\Entity\Product;
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
}
