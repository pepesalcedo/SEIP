<?php

namespace Brown\MunicipioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MunicipioBundle:Default:index.html.twig', array('name' => $name));
    }
}
