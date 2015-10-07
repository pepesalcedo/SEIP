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
use AppBundle\Entity\GrupoRecurso;
use AppBundle\Entity\TipoGrupoRecurso;
use AppBundle\Form\GrupoRecursoForm;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use FOS\RestBundle\Controller\Annotations as Rest;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
/**
 * Description of GruposRecursoController
 *
 * @author Jose
 */
class GruposRecursoController extends BasicController {
    
    
    public function GrabarRecursosAsociados($session, $idrecurso)
    {
            // ya hemos guardado lo principal, !!! cuidado que no es transaccional, habrá que revisarlo mejor
            // ahora guardamos las relaciones
            $grupoRecursoMemoria = $session->get('grupoRecurso');
            $grupoRecursoSaved = $this->getDoctrine()
                    ->getRepository("AppBundle:GrupoRecurso")
                    ->find($idrecurso);
            
            $em = $this->getDoctrine()->getManager();

            // borro las entidades que ya no están 
            foreach($grupoRecursoSaved->getPersonas() as $persona)
            {
                if (!$grupoRecursoMemoria->isPersonInGroup($persona->getId())) {
                    $persona = $em->merge($persona);
                    $grupoRecursoSaved->removePersona($persona);
                }
            }

            // Añado las nuevas que me encuentro en memoria
            foreach($grupoRecursoMemoria->getPersonas() as $persona)
            {
                if (!$grupoRecursoSaved->isPersonInGroup($persona->getId())) {
                    $persona = $em->merge($persona);
                    $grupoRecursoSaved->addPersona($persona);
                    //$persona->addGruposRecurso($grupoRecursoSaved);
                }
            }

            // borro las entidades que ya no están 
            foreach($grupoRecursoSaved->getVehiculos() as $vehiculo)
            {
                if (!$grupoRecursoMemoria->isVehicleInGroup($vehiculo->getId())) {
                    $vehiculo = $em->merge($vehiculo);
                    $grupoRecursoSaved->removeVehiculo($vehiculo);
                }
            }

            // Añado las nuevas que me encuentro en memoria
            foreach($grupoRecursoMemoria->getVehiculos() as $vehiculo)
            {
                if (!$grupoRecursoSaved->isVehicleInGroup($vehiculo->getId())) {
                    $vehiculo = $em->merge($vehiculo);
                    $grupoRecursoSaved->addVehiculo($vehiculo);
                    //$persona->addGruposRecurso($grupoRecursoSaved);
                }
            }
            
          
            $em->persist($grupoRecursoSaved);
            //$em->persist($persona);
            $em->flush();
        
    }
    
    /** Devuelve el objeto en una página Ajax
     * @Route("/gruporecurso", name="gruporecursoedit")
     * @Route("/gruporecurso/{idrecurso}", name="gruporecursosubmitupdate")
     */
    public function grupoRecursoAction(Request $request, $idrecurso = 0)
    {
        $task = new GrupoRecurso();
        $task->setUsuarioalta ($this->getUser());

        $formTemplate = new GrupoRecursoForm();

        
        $session = $request->getSession();

        $strReturn = $this->editClaseRecurso($request, $idrecurso, "AppBundle:GrupoRecurso", $task, $formTemplate, "recurso/grupoRecurso.html.twig"  );   
    
        
        if ( $request->isMethod( 'POST' ) ) {
            if ($task->getEstado() && $task->getEstado()->getName() != 'Activa') {
                $task->setUsuariocierre ($this->getUser ());
            }
            $this->GrabarRecursosAsociados($session, $task->getId());
        }
        else
        {
            // le añado a la sesión el objeto para trabajar en memoria
            $personas = $task->getPersonas();
            $c = $personas->count();
            $cv = $task->getVehiculos()->count();
            $session->set('grupoRecurso', $task);
        }

        
        return $strReturn;
    }
    
    
    /** Borra el objeto
     * @Route("/gruporecursodelete", name="gruporecursodelete")
     * @Route("/gruporecursodelete/{idrecurso}", name="gruporecursodeleteParams")
     */
    public function grupoRecursoDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new GrupoRecurso();
        
        return $this->deleteRecurso($request,$task,  "AppBundle:GrupoRecurso", $idrecurso);

    }
    
       /** Borra el objeto
     * @Route("/gruporecursodeletepersona", name="gruporecursodeletepersona")
     * @Route("/gruporecursodeletepersona/{idPrincipal}/{idrecurso}", name="gruporecursodeletepersonaParams")
     */
    public function grupoRecursoDeletePersonaAction(Request $request, $idPrincipal = 0, $idrecurso = 0)
    {
        
        $id = $request->get('id');
        $idAdicional = $request->get('idAdicional');
        if ($id>0) {
            $idrecurso = $id;
        }
        
        if ($idAdicional > 0) {
            $idPrincipal = $idAdicional;
        }
        
        
        if ($idrecurso > 0)
        {
            // Consigo el objeto de memoria y le quito un recurso
            $session = $request->getSession();
            $task = $session->get('grupoRecurso');
            if ($task != null)
            {
                $personas = $task->getPersonas();
                
                foreach($personas as $personInTask)
                {
                    if ($personInTask->getId() == $idrecurso)
                    {
                        $task->removePersona($personInTask);
                    }
                }
                //$task->removePersona($persona);
            }
            
            $session->set('grupoRecurso', $task);
            /*$em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();*/

            $response['success'] = true;
            
            
        }else{

            $response['success'] = false;
            $response['cause'] = $form->getErrorsAsString();

        }

        return new JsonResponse( $response );


    }


       /** Borra el objeto
     * @Route("/gruporecursodeletevehiculo", name="gruporecursodeletevehiculo")
     * @Route("/gruporecursodeletevehiculo/{idPrincipal}/{idrecurso}", name="gruporecursodeletevehiculoparams")
     */
    public function grupoRecursoDeleteVehiculoAction(Request $request, $idPrincipal = 0, $idrecurso = 0)
    {
        
        $id = $request->get('id');
        $idAdicional = $request->get('idAdicional');
        if ($id>0) {
            $idrecurso = $id;
        }
        
        if ($idAdicional > 0) {
            $idPrincipal = $idAdicional;
        }
        
        
        if ($idrecurso > 0)
        {
            $session = $request->getSession();
            $task = $session->get('grupoRecurso');

            if ($task != null)
            {
                $vehiculos = $task->getVehiculos();
                
                foreach($vehiculos as $vehiculo)
                {
                    if ($vehiculo->getId() == $idrecurso)
                    {
                        $task->removeVehiculo($vehiculo);
                    }
                }
            }
            
            $session->set('grupoRecurso', $task);

            /* $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();*/

            $response['success'] = true;
        }
        else{

            $response['success'] = false;
            $response['cause'] = $form->getErrorsAsString();

        }

        return new JsonResponse( $response );


    }

    
    
    /**
     * @Route("/gruporecursogrid", name="gruporecursogrid")
     */
    public function grupoRecursoGridAction()
    {
        $source = new Entity('AppBundle:GrupoRecurso');
            
        return $this->showBasicGrid($source, "gruporecursoedit", "gruporecursodelete", "Grupo Recurso", "Grupos Recursos");
    }

    
    /**
     * @Route("/gruporecursopersonasgridajax/{idrecurso}", name="gruporecursopersonasgridajax")
     */
    public function grupoRecursoPersonasGridAjaxAction(Request $request, $idrecurso)
    {

        $session = $request->getSession();
        $grupoRecurso = $session->get('grupoRecurso');
        $personas = $grupoRecurso->getPersonas();
        
        $arrayPersonas = array();
        foreach ($personas as $persona )
        {
            array_push($arrayPersonas, array(
                'id' => $persona->getId(), 
                'dni' => $persona->getDni(), 
                'Nombre' => $persona->getNombre(),
                'Apellido' => $persona->getApellido()   ));
            
        }
        
        if ($personas->count() == 0)
        {
            array_push($arrayPersonas, array(
                'id' => 0, 
                'data' => "Sin datos", ));
        }
        //$arrayPersonas =  $personas->toArray();
        
        
        $source = new Vector($arrayPersonas);

        $grid = $this->getGrid($source, "", "gruporecursodeletepersona", array(), $idrecurso);
        $grid->hideColumns('id');
        $grid->hideFilters();
        //$grid->setDefaultFilters(array());

        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenericoAjax.html.twig',array());

          
          //return $this->showBasicAjaxGrid($source, "", "gruporecursodeletepersona", "Grupo Recurso", "Grupos Recursos", array('dni','fechaAlta', 'profesion', 'regimen', 'estado.name'));
    }


    /**
     * @Route("/gruporecursovehiculosgridajax/{idrecurso}", name="gruporecursovehiculosgridajax")
     */
    public function grupoRecursoVehiculosGridAjaxAction(Request $request, $idrecurso)
    {
        
        $session = $request->getSession();
        $grupoRecurso = $session->get('grupoRecurso');
        $vehiculos = $grupoRecurso->getVehiculos();
        
        $arrayVehiculos = array();
        foreach ($vehiculos as $vehiculo)
        {
            array_push($arrayVehiculos, array(
                'id' => $vehiculo->getId(), 
                'Patente' => $vehiculo->getPatente(), 
                'Tipo Vehículo' => $vehiculo->getTipovehiculo()
                ));
            
        }
        //$arrayPersonas =  $personas->toArray();
        
        if ($vehiculos->count() == 0)
        {
            array_push($arrayVehiculos, array(
                'id' => 0, 
                'data' => "Sin datos", ));
        }        
        
        
        $source = new Vector($arrayVehiculos);

        $grid = $this->getGrid($source, "", "gruporecursodeletevehiculo", array(), $idrecurso);
        $grid->hideColumns('id');
        $grid->hideFilters();


        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenericoAjax.html.twig',array());
        

/*        $source = new Entity('AppBundle:RecursoVehiculo');

        $tableAlias = $source->getTableAlias();

        $source->manipulateQuery(
             function ($query) use ($tableAlias, $idrecurso)
                {
                    $query->innerJoin($tableAlias.".gruposRecurso", "gr");
                    $query->andWhere("gr.id= ".$idrecurso);
                }
          );
        
          
        $grid = $this->getGrid($source, "", "gruporecursodeletevehiculo", array('fechaAlta', 'regimen', 'estado.name'), $idrecurso);

        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenericoAjax.html.twig',array());
*/
          
          //return $this->showBasicAjaxGrid($source, "", "gruporecursodeletepersona", "Grupo Recurso", "Grupos Recursos", array('dni','fechaAlta', 'profesion', 'regimen', 'estado.name'));
    }
    
    
    
    /** 
     * @Rest\Get(path="/api/tipogruporecurso")
     * @Rest\View()
     */
    public function getAllTipoRecursosAction(Request $request)
    {


        $productsPager = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:TipoGrupoRecurso');
        $products = $productsPager->findAll();

        $serializer = $this->container->get('serializer');
        $reports = $serializer->serialize($products, 'json');
        return new Response($reports); // should be $reports as $doctrineobject is not serialized
        //return new JsonResponse($products);
    /*    $repository = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:TipoGrupoRecurso');

        return $repository->findAll();*/
    }
    

    /**
     *  Callback del grid con las personas seleccionadas, hay que guardarlo
     * @param type $primaryKeys
     * @param type $allPrimaryKeys
     * @param type $idGrupo
     * 
     */
    public function CallBackPersonasAction(Request $request, $primaryKeys, $allPrimaryKeys, $idGrupo)
    {
        $idrecurso = $idGrupo;
        
        // Consigo las personas del grupo
        $session = $request->getSession();
        $grupoRecurso = $session->get('grupoRecurso');
        /*$grupoRecurso = $this->getDoctrine()
                    ->getRepository("AppBundle:GrupoRecurso")
                    ->find($idrecurso); */

        if ($allPrimaryKeys)
        {
            $personas = $this->getDoctrine()
                    ->getRepository("AppBundle:RecursoPersona")
                    ->findAll();

            foreach ($personas as $persona ) {
                if (!$grupoRecurso->isPersonInGroup($persona->getId())) {
                    $grupoRecurso->addPersona($persona);
                }
            }            
        }
        else
        {
            foreach ($primaryKeys as $id)
            {
                if (!$grupoRecurso->isPersonInGroup($id))
                {
                    $persona = $this->getDoctrine()
                        ->getRepository("AppBundle:RecursoPersona")
                        ->find($id);
                    $grupoRecurso->addPersona($persona);
                }
            }
        }
        
        $session->set('grupoRecurso', $grupoRecurso);
        /*$em = $this->getDoctrine()->getManager();
        $em->persist($grupoRecurso);
        $em->flush();*/

        $response['success'] = true;
        return new JsonResponse( $response );

    }


    /**
     * @Route("/personasgridajax/{idrecurso}", name="personasgridajax")
     */
    public function PersonasGridAjaxAction(Request $request, $idrecurso = 0)
    {
        $source = new Entity('AppBundle:RecursoPersona');
        $recursoedit = "";
        $recursodelete = "";
        $params = array('idGrupo' => $idrecurso);
        
        return $this->showSelectAjaxGrid($source, $recursoedit, $recursodelete, "Recurso", "Recursos", 'AppBundle:GruposRecurso:CallBackPersonas', $params );

    }

    
    /**
     *  Callback del grid con llas personas seleccionadas, hay que guardarlo
     * @param type $primaryKeys
     * @param type $allPrimaryKeys
     * @param type $idGrupo
     * 
     */
    public function CallBackVehiculosAction(Request $request, $primaryKeys, $allPrimaryKeys, $idGrupo)
    {
        $idrecurso = $idGrupo;
        
        $session = $request->getSession();
        $grupoRecurso = $session->get('grupoRecurso');
        
        if ($allPrimaryKeys)
        {
            $vehiculos = $this->getDoctrine()
                    ->getRepository("AppBundle:RecursoVehiculo")
                    ->findAll();

            foreach ($vehiculos as $vehiculo ) {
                if (!$grupoRecurso->isVehicleInGroup($vehiculo->getId())) {
                    $grupoRecurso->addVehiculo($vehiculo);
                }
            }            
        }
        else
        {
            foreach ($primaryKeys as $id)
            {
                if (!$grupoRecurso->isVehicleInGroup($id))
                {
                    $vehiculo = $this->getDoctrine()
                        ->getRepository("AppBundle:RecursoVehiculo")
                        ->find($id);
                    $grupoRecurso->addVehiculo($vehiculo);
                }
            }
        }
        
        $session->set('grupoRecurso', $grupoRecurso);

        /*$em = $this->getDoctrine()->getManager();
        $em->persist($grupoRecurso);
        $em->flush();*/

        $response['success'] = true;
        return new JsonResponse( $response );

    }

    
    
    
    /**
     * @Route("/vehiculosgridajax/{idrecurso}", name="vehiculosgridajax")
     */
    public function VehiculosGridAjaxAction(Request $request, $idrecurso = 0)
    {
        $source = new Entity('AppBundle:RecursoVehiculo');
        $recursoedit = "";
        $recursodelete = "";
        $params = array('idGrupo' => $idrecurso);
        
        return $this->showSelectAjaxGrid($source, $recursoedit, $recursodelete, "Recurso", "Recursos", 'AppBundle:GruposRecurso:CallBackVehiculos', $params );

    }
    


    
}


