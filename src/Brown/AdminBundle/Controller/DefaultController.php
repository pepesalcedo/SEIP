<?php

namespace Brown\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->get('white_october_breadcrumbs')->addItem('Administración', $this->get('router')->generate('admin_homepage'));
        return $this->render('AdminBundle:Default:index.html.twig');
    }
}
