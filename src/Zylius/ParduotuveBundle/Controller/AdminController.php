<?php

namespace Zylius\ParduotuveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Renders a page with logs.
     *
     * @param int $page
     * @return Response
     */
    public function indexAction($page = 0)
    {
        $perPage = 10;
        $logs = $this->getDoctrine()->getRepository('ZyliusParduotuveBundle:LogItem')->findBy([], null, $perPage, $perPage * $page);
        return $this->render(
            'ZyliusParduotuveBundle:Admin:index.html.twig',
            ['logs' => $logs, 'page' => $page]
        );
    }
}
