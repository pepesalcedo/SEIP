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
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\CentroAtencion;
use AppBundle\Entity\TipoCentroAtencion;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;
use APY\DataGridBundle\Grid\Source\Entity;
use AppBundle\Form\CentroAtencionForm;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class CentrosAtencionController extends BasicController {

     
        /**
     * @Rest\Get(path="/api/centro/{idrecurso}")
     * @Rest\View()
     */
    public function getCentroAction($idrecurso)
    {
        $centro = $this->getDoctrine()
                ->getRepository('AppBundle:CentroAtencion')
                ->find($idrecurso);
        $serializer = $this->container->get('serializer');
        $centroSerialized = $serializer->serialize($centro, 'json');
        return new Response($centroSerialized);
    }
    
    /** Devuelve el objeto en una página Ajax
     * @Route("/centroatencion", name="centroatencionedit")
     * @Route("/centroatencion/{idrecurso}", name="centroatencionsubmitupdate")
     */

    public function centroAtencionAction(Request $request, $idrecurso = 0)
    {
        $task = new CentroAtencion();
        $formTemplate = new CentroAtencionForm();


        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:CentroAtencion", $task, $formTemplate, "centroatencion/centroatencion.html.twig"  );   

    
    }
    
    /** Borra el objeto
     * @Route("/centroatencionDetete", name="centroatenciondelete")
     * @Route("/centroatencionDetete/{idrecurso}", name="centroatenciondeleteParams")
     */
    public function centroAtencionDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new CentroAtencion();
        return $this->deleteRecurso($request, $task, "AppBundle:CentroAtencion", $idrecurso);

    }

    
    
    

    /** Devuelve los tipos de centro de atención
     * @Route("/tipocentroatencion", name="tipocentroatencionedit")
     * @Route("/tipocentroatencion/{idrecurso}", name="tipocentroAtencionedit2")
     */
    public function tipoCentroAtencionAction(Request $request, $idrecurso = 0)
    {
        $task = new TipoCentroAtencion();
        $route = "tipocentroAtencionedit2";
        $entity = "AppBundle:TipoCentroAtencion";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);

    }
    
    /** Borra el objeto
     * @Route("/tipocentroatenciondelete", name="tipocentroatenciondelete")
     * @Route("/tipocentroatenciondelete/{idrecurso}", name="tipocentroatenciondelete2")
     */
    public function tipocentroAtencionDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new TipoCentroAtencion();
        return $this->deleteRecurso($request, $task, "AppBundle:TipoCentroAtencion", $idrecurso);
    }

    
    
    /**
     * @Route("/tipocentroatenciongrid", name="tipocentroatenciongrid")
     */
    public function tipoCentroAtencionGridAction()
    {
        $source = new Entity('AppBundle:TipoCentroAtencion');
            
        return $this->showBasicGrid($source,"tipocentroatencionedit", "tipocentroatenciondelete", "Tipo Centro Atención", "Tipos Centro Atención");
    }

    /**
     * @Route("/centroatenciongrid", name="centroatenciongrid")
     */
    public function centroAtencionGridAction()
    {
        $source = new Entity('AppBundle:CentroAtencion');
            
        return $this->showBasicGrid($source, "centroatencionedit", "centroatenciondelete", "Centro Atención", "Centros Atención");
    }
    
         /** 
      * Devuelve los centros filtrados por tipo
     * @Route("/selectcentros", name="select_centros")
     */
    public function selectCentrosAction(Request $request)
    {
        $tipo_id = $request->request->get('tipo_id');

        $em = $this->getDoctrine()->getManager();
        $centros = $em->getRepository('AppBundle:CentroAtencion')
            ->findBy(array('tipo' => $tipo_id));
        
        //return new Response(json_encode($cities));
        
        $serializer = $this->container->get('serializer');                
        $centrosSerializer = $serializer->serialize($centros, 'json');
        return new Response($centrosSerializer);
        //return new JsonResponse($cities);
    }

    
    

}
