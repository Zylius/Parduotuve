<?php

namespace Visciukai\GalerijaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VisciukaiGalerijaBundle:Default:index.html.twig');
    }
}
