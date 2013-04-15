<?php

namespace Zmc\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ZmcDemoBundle:Default:index.html.twig', array('name' => $name));
    }
}
