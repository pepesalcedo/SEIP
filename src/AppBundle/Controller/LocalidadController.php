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
use AppBundle\Entity\Provincia;
use AppBundle\Entity\Localidad;
use AppBundle\Form\LocalidadForm;

use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;

/**
 * Description of LocalidadController
 *
 * @author Jose
 */
class LocalidadController extends BasicController {
    
     /** 
      * Return cities filtered by province_id included in the request
     * @Route("/cities", name="select_cities")
     */
    public function citiesAction(Request $request)
    {
        $province_id = $request->request->get('province_id');

        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('AppBundle:Localidad')
            ->findBy(array('provincia' => $province_id));
        
        //return new Response(json_encode($cities));
        
        $serializer = $this->container->get('serializer');                
        $citiesSerializer = $serializer->serialize($cities, 'json');
        return new Response($citiesSerializer);
        //return new JsonResponse($cities);
    }
    
    
     /**
     * @Route("/localidad" , name="localidadedit")
     * @Route("/localidad/{idrecurso}" , name="localidadeditparams")
     */
    public function LocalidadAction($idrecurso = 0, Request $request)
    {
        $id = $request->get('id');
        if ($id>0)
            $idrecurso = $id;
        $task = new Localidad();
        $formTemplate = new LocalidadForm();
        $formTemplate->setAction($this->generateUrl('localidadeditparams',array('idrecurso' => $idrecurso)));
       

        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:Localidad", $task, $formTemplate, "generico/generico.html.twig"  );   
        
                        
    }
    
        
    /** Borra el objeto
     * @Route("/localidaddelete", name="localidaddelete")
     * @Route("/localidaddelete/{idrecurso}", name="localidaddeleteParams")
     */
    public function localidadDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new Localidad();
        return $this->deleteRecurso($request, $task, "AppBundle:Localidad", $idrecurso);
    }

    
    
    /**
     * @Route("/localidadgrid", name="localidadgrid")
     */
    public function localidadGridAction()
    {
        $source = new Entity('AppBundle:Localidad');
            
        return $this->showBasicGrid($source,"localidadedit", "localidaddelete", "Localidad", "Localidades");
    }
    
       /**
     * @Route("/provincia", name="provinciaedit" )
     * @Route("/provincia/{idrecurso}", name="provinciaeditparams")
     */
    public function ProvinciaAction(Request $request, $idrecurso = 0)
    {
        $task = new Provincia();
        $route = "provinciaeditparams";
        $entity = "AppBundle:Provincia";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);
    }

    /** Borra el objeto
     * @Route("/provinciadelete", name="provinciadelete")
     * @Route("/provinciadelete/{idrecurso}", name="provinciadeleteParams")
     */
    public function provinciaDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new Provincia();
        return $this->deleteRecurso($request, $task, "AppBundle:Provincia", $idrecurso);
    }

    
    
    /**
     * @Route("/provinciagrid", name="provinciagrid")
     */
    public function provinciaGridAction()
    {
        $source = new Entity('AppBundle:Provincia');
            
        return $this->showBasicGrid($source,"provinciaedit", "provinciadelete", "Provincia", "Provincias");
    }
    
    
    
        }
