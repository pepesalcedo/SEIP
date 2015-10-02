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
use AppBundle\Entity\RecursoPersona;
use AppBundle\Entity\RecursoVehiculo;
use AppBundle\Entity\TipoGrupoRecurso;
use AppBundle\Entity\ClaseRecurso;

use APY\DataGridBundle\Grid\Source\Entity;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\RecursoGridBaseForm;
use AppBundle\Form\RecursoPersonaForm;
use AppBundle\Form\RecursoVehiculoForm;

/**
 * Description of ClaseRecursoController
 *
 * @author Jose
 */
class RecursoController extends BasicController {

    
    
    
    /** Devuelve el objeto en una página Ajax
    * Esta función solo se llama para determinar que ventana hay que abrir la de vehículo o la de personas, para hacer el submit se llamará a la ruta correspondiente de persona o vehículo
     * @Route("/recurso", name="recursoedit")
     * @Route("/recurso/{idrecurso}", name="recursosubmitupdate")
     */
    public function RecursoAction(Request $request, $idrecurso = 0)
    {
        
        $idClaseRecurso = $request->get('claserecurso_id');
        
        // Si pasan el id de la clase de recurso, quiere decir que es nuevo y debo cargar la clase de recurso por defecto
        if ($idClaseRecurso != null)
        {
            $claseRecurso = $this->getDoctrine()
            ->getRepository("AppBundle:ClaseRecurso")
            ->find($idClaseRecurso); 
            
            // si el tipo es de vehículo debo cambiar los objetos y trabajar con los objetos de vehículo
            if ($claseRecurso->isVehiculo())
            {
                return $this->RecursoVehiculoAction($request, $idrecurso);
                /*$task = new RecursoVehiculo();
                $task->setClaserecurso($claseRecurso);
                $formTemplate = new RecursoVehiculoForm();
                
                return $this->editClaseRecurso($request, $idrecurso, "AppBundle:RecursoVehiculo", $task, $formTemplate, "recurso/recursoVehiculo.html.twig"  );   */

            }
            else
            {
               return $this->RecursoPersonaAction($request, $idrecurso);
            }
            
        }
        
        //return $this->editClaseRecurso($request, $idrecurso, "AppBundle:RecursoPersona", $task, $formTemplate, "recurso/recurso.html.twig"  );   
    
    }
    

    /** Edita, graba, modifica una persona
     * @param Request $request
     * @param type $idrecurso  indica el id de recurso para saber que tipo de recurso hay que crear
     * @return type
     * 
     * @Route("/recursopersona", name="recursopersonaedit")
     * @Route("/recursopersona/{idrecurso}", name="recursopersonasubmitupdate")
     */
    public function RecursoPersonaAction(Request $request, $idrecurso = 0)
    {
        
        $idClaseRecurso = $request->get('claserecurso_id');
        
        $task = new RecursoPersona();
        $formTemplate = new RecursoPersonaForm();

        // Si pasan el id de la clase de recurso, quiere decir que es nuevo y debo cargar la clase de recurso por defecto
        if ($idClaseRecurso != null)
        {
            $claseRecurso = $this->getDoctrine()
            ->getRepository("AppBundle:ClaseRecurso")
            ->find($idClaseRecurso); 
            
            $task->setClaserecurso($claseRecurso);
        }
        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:RecursoPersona", $task, $formTemplate, "recurso/recurso.html.twig"  );   
    
    }
    
    /** Edita, graba, modifica una persona
     * @param Request $request
     * @param type $idrecurso  indica el id de recurso para saber que tipo de recurso hay que crear
     * @return type
     * 
     * @Route("/recursovehiculo", name="recursovehiculoedit")
     * @Route("/recursovehiculo/{idrecurso}", name="recursovehiculosubmitupdate")
     */
    public function RecursoVehiculoAction(Request $request, $idrecurso = 0)
    {
        
        $idClaseRecurso = $request->get('claserecurso_id');
        
        $task = new RecursoVehiculo();
        $formTemplate = new RecursoVehiculoForm();

        // Si pasan el id de la clase de recurso, quiere decir que es nuevo y debo cargar la clase de recurso por defecto
        if ($idClaseRecurso != null)
        {
            $claseRecurso = $this->getDoctrine()
            ->getRepository("AppBundle:ClaseRecurso")
            ->find($idClaseRecurso); 
            
            $task->setClaserecurso($claseRecurso);
        }
        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:RecursoVehiculo", $task, $formTemplate, "recurso/recursoVehiculo.html.twig"  );   
    
    }
    
    /** Borra el objeto
     * @Route("/recursopersonadelete", name="recursopersonadelete")
     * @Route("/recursopersonandelete/{idrecurso}", name="recursopersonadeleteParams")
     */
    public function RecursoPersonaDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new RecursoPersona();
        
        return $this->deleteRecurso($request,$task,  "AppBundle:RecursoPersona", $idrecurso);

    }

    /** Borra el objeto
     * @Route("/recursovehiculodelete", name="recursovehiculodelete")
     * @Route("/recursovehiculodelete/{idrecurso}", name="recursovehiculodeleteParams")
     */
    public function RecursoVehiculoDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new RecursoVehiculo();
        
        return $this->deleteRecurso($request,$task,  "AppBundle:RecursoVehiculo", $idrecurso);

    }

    /**
     * @Route("/recursogrid", name="recursogrid")
     */    
    public function RecursoGridAction(Request $request)
    {
        $task = new RecursoPersona();
        $formTemplate = new RecursoGridBaseForm();
        
         $form = $this->createForm($formTemplate, $task);

        $view = $this->view($form, 200)
            ->setTemplate("recurso/recursoGrid.html.twig")
            ->setTemplateVar('generico');

        return $this->handleView($view);

    }
    
    
    /**
     * @Route("/recursogridajax", name="recursogridajax")
     */
    public function RecursoGridAjaxAction(Request $request)
    {
        $idClaseRecurso = $request->get('claserecurso_id');
        
        $session = $request->getSession();

        if (!$idClaseRecurso)
        {
            // lo consigo de la sesión 
            $idClaseRecurso = $session->get('parametroClaseRecurso');
        }
        
        // Para mantener el parámetro para cuando trabajen con el grid ajax
        $session->set('parametroClaseRecurso', $idClaseRecurso);
        
        $claseRecurso = $this->getDoctrine()
            ->getRepository("AppBundle:ClaseRecurso")
            ->find($idClaseRecurso); 

        if ($claseRecurso != null)
        {
            if ($claseRecurso->isVehiculo())
            {
                $source = new Entity('AppBundle:RecursoVehiculo');
                $recursoedit = "recursovehiculoedit";
                $recursodelete ="recursovehiculodelete";
            }
            else
            {
                $source = new Entity('AppBundle:RecursoPersona');
                $recursoedit = "recursopersonaedit";
                $recursodelete = "recursopersonadelete";
           }
        }
        
        
        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(
             function ($query) use ($tableAlias, $idClaseRecurso)
                {

                    $query->andWhere("_claserecurso.id= ".$idClaseRecurso);
                }
            );
            
            
        return $this->showBasicAjaxGrid($source, $recursoedit, $recursodelete, "Recurso", "Recursos");

    }

    
    
}
