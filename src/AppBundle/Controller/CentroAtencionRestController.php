<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CentroAtencion;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;
use AppBundle\Form\CentroAtencionForm;
use AppBundle\Entity\BasicEntity;
use APY\DataGridBundle\Grid\Source\Entity;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class CentroAtencionRestController extends FOSRestController {

    /**
     * @Route("/prueba")
     */
    public function prueba(Request $request)
    {
        return $this->render('pruebaAjax.html.twig');
    }
     
    
    
    /**
     * @Route("/centroatencionrest", name="centroatencionrestedit")
     * @Route("/centroatencionrest/{idrecurso}")
     */
    public function centroAtencionRestAction(Request $request, $idrecurso = 0)
    {
    $task = new CentroAtencion();

    $id = $request->get('id');
    if ($id>0)
        $idrecurso = $id;

    if ($idrecurso > 0)
    {
           $task = $this->getDoctrine()
                ->getRepository('AppBundle:CentroAtencion')
                ->find($idrecurso); 
    }
    
    
    $propertyPathToCity = 'localidad';
 
    
    $form = $this->createForm(new CentroAtencionForm, $task);
    

    $form->handleRequest($request);

    if ($form->isValid()) {
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($task);
        $em->flush();
        // perform some action, such as saving the task to the database
            return new Response('Created servicio id '. $task->getId());

    }
    
        //$source = new Entity('AppBundle:CentroAtencion');
            
        return $this->render('fichaCentro.html.twig', array(
          'form' => $form->createView()
      ));
        
        
    }
    

    /**
     * @Route("/getEntity/{idR}")
     */       
    public function getEntityAction($idR)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:CentroAtencion')->find($idR);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find organisation entity');
        }

        return $entity;
    }
    
    /**
     * @Route("/centroNewRest")
     */
    public function newAction()
    {
        return $this->processForm(new CentroAtencion());
    }
    
    /**
     * @Route("/centroRest/{idrecurso}")
     */
    public function getAction($idrecurso)
    {
        
        $centroAtencion = $this->getDoctrine()
                ->getRepository('AppBundle:CentroAtencion')
                ->find($idrecurso); 

        $centroAtencion->setNew(false);
        return $this->processForm($centroAtencion);
    }

    
    private function processForm(CentroAtencion $centro)
    {
        $statusCode = $centro->isNew() ? 201 : 204;

        $form = $this->createForm(new CentroAtencionForm(), $centro);
        //$form->bind();
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($centro);
            $em->flush();
            

            $response = new Response("Creado Centro Atencion " . $centro->getId());
            //$response->setStatusCode($statusCode);
            //$response-
            
            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location',
                    $this->generateUrl(
                        'acme_demo_user_get', array('id' => $centro->getId()),
                        true // absolute
                    )
                );
            }

            return $response;
        }

        $view = $this->view($form, 200)
            ->setTemplate("fichaCentro.html.twig")
            ->setTemplateVar('centro')
        ;

        return $this->handleView($view);
        
        //$view = $this->view($centro, 400)
                    ;

        //return $this->handleView($view);

        //return View::create($form, 400);
    }
}


