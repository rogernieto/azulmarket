<?php

namespace RANH\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RANHUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
