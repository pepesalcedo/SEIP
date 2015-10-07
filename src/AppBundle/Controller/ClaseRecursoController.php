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
use AppBundle\Entity\ClaseRecurso;
use AppBundle\Entity\TipoGrupoRecurso;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;
use APY\DataGridBundle\Grid\Source\Entity;
use AppBundle\Form\ClaseRecursoForm;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Description of ClaseRecursoController
 *
 * @author Jose
 */
class ClaseRecursoController extends BasicController {

    /** Devuelve el objeto en una página Ajax
     * @Route("/claserecurso", name="claserecursoedit")
     * @Route("/claserecurso/{idrecurso}", name="claserecursosubmitupdate")
     */
    public function claseRecursoAction(Request $request, $idrecurso = 0)
    {
        $task = new ClaseRecurso();
        $formTemplate = new ClaseRecursoForm();


        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:ClaseRecurso", $task, $formTemplate, "recurso/claseRecurso.html.twig"  );   
    
    }
    
    
    /** Borra el objeto
     * @Route("/claserecursodetete", name="claserecursodelete")
     * @Route("/claserecursondetete/{idrecurso}", name="claserecursodeleteParams")
     */
    public function claseRecursoDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new ClaseRecurso();
        
        return $this->deleteRecurso($request,$task,  "AppBundle:ClaseRecurso", $idrecurso);

    }

    
    
    

    /** Devuelve los tipos de centro de atención
     * @Route("/tipogruporecurso", name="tipogruporecursoedit")
     * @Route("/tipogruporecurso/{idrecurso}", name="tipogruporecursoedit2")
     */
    public function tipoGrupoRecursoAction(Request $request, $idrecurso = 0)
    {
        $task = new TipoGrupoRecurso();
        $route = "tipogruporecursoedit2";
        $entity = "AppBundle:TipoGrupoRecurso";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);
    }
    
    /** Borra el objeto
     * @Route("/tipogruporecursodelete", name="tipogruporecursodelete")
     * @Route("/tipogruporecursodelete/{idrecurso}", name="ttipogruporecursodelete2")
     */
    public function tipoGrupoRecursoDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new TipoGrupoRecurso();
        return $this->deleteRecurso($request, $task, "AppBundle:TipoGrupoRecurso", $idrecurso);
    }

    
    
    /**
     * @Route("/tipogruporecursogrid", name="tipogruporecursogrid")
     */
    public function tipoGrupoRecursoGridAction()
    {
        $source = new Entity('AppBundle:TipoGrupoRecurso');
            
        return $this->showBasicGrid($source,"tipogruporecursoedit", "tipogruporecursodelete", "Tipo Grupo Recurso", "Tipos Grupo Recurso");
    }

    /**
     * @Route("/claserecursogrid", name="claserecursogrid")
     */
    public function claseRecursoGridAction()
    {
        $source = new Entity('AppBundle:ClaseRecurso');
            
        return $this->showBasicGrid($source, "claserecursoedit", "claserecursodelete", "Clase Recurso", "Clases Recursos");
    }
    

 
/**
 * @Route("/selectclaserecurso", name="select_claserecurso")
 */
public function selectClaseRecursoAction(Request $request)
{
    $tipoGrupoRecurso = $request->get('tipogruporecurso_id');
    $clasetipo = $request->get('clasetipo');
 
    $em = $this->getDoctrine()->getManager();
    
    $clasesRecurso = $em->getRepository('AppBundle:ClaseRecurso')
            ->findBy(array('tipo' => $tipoGrupoRecurso, 'clase' => $clasetipo));

    //return new Response(json_encode($cities));

    $serializer = $this->container->get('serializer');                
    $objectSerializer = $serializer->serialize($clasesRecurso, 'json');
    return new Response($objectSerializer);

 
    //return new JsonResponse($clasesRecurso);
}
    
    
}
