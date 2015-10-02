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
use AppBundle\Entity\BasicEntity;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use AppBundle\Entity\EstadoTabla;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Description of BasicController
 *
 * @author Jose
 */
class BasicController extends FOSRestController {

    
    /**
     * return a common grid for all the aplication
     * @param type $source
     * @param type $route_to_edit
     * @param type $route_to_delete
     * @return type
     */
    public function getGrid($source, $route_to_edit="", $route_to_delete="", $columnsToHide=array(), $idAdicional = 0)
    {
        // get grid instance
        $grid = $this->get('grid');
        
        // Set the selector of the number of items per page
        $grid->setLimits(array(10, 15, 20));

        // Set the default page
        $grid->setDefaultPage(1);
        
        // Attach the source to the grid
        $grid->setSource($source);
        
        
        $grid->hideColumns($columnsToHide);
        // Add row actions in the default row actions column
        //$myRowAction = new RowAction('Edit', 'app_gruposrecurso_tipogruporecurso');
        
        if ($route_to_edit != "")
        {            
            $MyColumn = new \AppBundle\Grid\SEIPActionsColumn();
            $MyColumn->setEditRoute($route_to_edit);
            $MyColumn->setDeleteRoute($route_to_delete);
            $MyColumn->setClass("actionsGrid");
            $MyColumn->setIdRutaAdicional($idAdicional);

            $grid->addColumn($MyColumn);
            
        }
        else if ($route_to_delete != "")
        {
            $MyColumn = new \AppBundle\Grid\SEIPActionsColumn();
            $MyColumn->setDeleteRoute($route_to_delete);
            $MyColumn->setIdRutaAdicional($idAdicional);
            $MyColumn->setClass("actionsGrid");

            $grid->addColumn($MyColumn);
            
        }
        
        return $grid;
    }
    
    /**
     * Action to show basic grid
     * @param type $source
     * @param type $route_to_edit
     * @param type $route_to_delete
     * @param type $titleWindow
     * @param type $titleGrid
     * @return type
     */
    public function showBasicGrid($source, $route_to_edit="", $route_to_delete="", $titleWindow = "Detalles", $titleGrid = "Listado")
    {
                // Creates a simple grid based on your entity (ORM)
        //$source = new Entity('MyProjectMyBundle:MyEntity');

        // Get a Grid instance
        $grid = $this->getGrid($source, $route_to_edit, $route_to_delete);

        $uriEdit = $this->generateUrl($route_to_edit);
        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenerico.html.twig',array('titleWindow' => $titleWindow, 'titleGrid' => $titleGrid, 'routeEdit' =>$uriEdit));
    }
    

    public function showBasicAjaxGrid($source, $route_to_edit="", $route_to_delete="", $titleWindow = "Detalles", $titleGrid = "Listado", $columnsToHide = array())
    {
                // Creates a simple grid based on your entity (ORM)
        //$source = new Entity('MyProjectMyBundle:MyEntity');

        // Get a Grid instance
        $grid = $this->getGrid($source, $route_to_edit, $route_to_delete, $columnsToHide);

        $uriEdit = "";
        if ($route_to_edit != null && $route_to_edit != "") {
            $uriEdit = $this->generateUrl($route_to_edit);
        }
        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenericoAjax.html.twig',array('titleWindow' => $titleWindow, 'titleGrid' => $titleGrid, 'routeEdit' =>$uriEdit));
    }
    


    
    public function showSelectAjaxGrid($source, $route_to_edit="", $route_to_delete="", $titleWindow = "Detalles", $titleGrid = "Listado", $selectCallback="", $params=array())
    {
        // Get a Grid instance
        $grid = $this->getGrid($source, $route_to_edit, $route_to_delete);
        
        // Add a mass action with object callback
        $yourMassAction = new MassAction('Seleccionar', $selectCallback);
        $yourMassAction->setParameters($params);
        $grid->addMassAction($yourMassAction);


        $uriEdit = "";
        if ($route_to_edit != null && $route_to_edit != "") {
            $uriEdit = $this->generateUrl($route_to_edit);
        }
        
        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenericoSelectAjax.html.twig',array('titleWindow' => $titleWindow, 'titleGrid' => $titleGrid, 'routeEdit' =>$uriEdit));
    }
    
    
    public function ShowBasicEntity(Request $request, $task)
    {
    $form = $this->createFormBuilder($task)
        ->add('name', 'text', array('label' => 'Descripción:'))
        ->add('save', 'submit', array('label' => 'Guardar'))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($task);
        $em->flush();
        // perform some action, such as saving the task to the database
            return new Response('Created tipo grupo recurso id '. $task->getId());

    }
    
        return $this->render('generico\generico.html.twig', array(
          'form' => $form->createView(),
      ));
    }
    
    public function ShowBasicEntityAjax(Request $request, $entity, $task, $route, $idrecurso)
    {
     
        $id = $request->get('id');
        if ($id>0)
            $idrecurso = $id;
        
        
        if ($idrecurso > 0)
        {
               $task = $this->getDoctrine()
                    ->getRepository($entity)
                    ->find($idrecurso); 
        }

    $action = $this->generateUrl($route, array('idrecurso' => $idrecurso));
    $form = $this->createFormBuilder($task)
        ->setAction($action)
        ->add('name', 'text', array('label' => 'Descripción:'))
        ->add('save', 'submit', array('label' => 'Guardar'))
        ->getForm();

    $form->handleRequest($request);

    if ( $request->isMethod( 'POST' ) ) {


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $response['success'] = true;

        }else{
            $response['success'] = false;
            $response['cause'] = $form->getErrorsAsString();
        }
       
        return new JsonResponse( $response );
    }
    
        $view = $this->view($form, 200)
            ->setTemplate("generico\generico.html.twig")
            ->setTemplateVar('generico')
        ;

        return $this->handleView($view);
    
    
 /*   return $this->render('generico\generico.html.twig', array(
          'form' => $form->createView(),
      ));*/
    }

    public function deleteRecurso(Request $request,$task,  $bundle, $idrecurso = 0)
    {

        $id = $request->get('id');
        if ($id>0)
            $idrecurso = $id;

        if ($idrecurso > 0)
        {
            try
            {
                $task = $this->getDoctrine()
                     ->getRepository($bundle)
                     ->find($idrecurso); 

                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();
                $response['success'] = true;
            }
            catch (\Exception $e)
            {
                $response['success'] = false;
                $response['cause'] = "No se puede borrar el registro, " . $e->getMessage();
                
            }
            
        }else{

            $response['success'] = false;
            $response['cause'] = $form->getErrorsAsString();

        }

        return new JsonResponse( $response );
    }
    
    
   /**
    * Saves form data
    * @param type $form
    * @return JsonResponse
    */
    public function saveData($form)
   {
    if ( $form->isValid( ) ) {
         $data = $form->getData();


         try
         {
          $em = $this->getDoctrine()->getManager();             
          $em->persist($data);
          $em->flush();
          
          $response['success'] = true;
         } catch (\Exception $e) {

             $response['success'] = false;
             $response['cause'] = $e->getMessage();

         }

      }else{

          $response['success'] = false;
          $response['cause'] = $form->getErrorsAsString();

      }

      return new JsonResponse( $response );
       
   }
    
    
    /**
     * Base function for create form with the parameterers
     * @param Request $request
     * @param type $idrecurso
     * @param type $entity
     * @param type $task
     * @param \AppBundle\Controller\AbstractType $formTemplate
     * @param type $twigTemplate
     * @return JsonResponse
     */
    public function editClaseRecurso(Request $request, $idrecurso, $entity, &$task, $formTemplate, $twigTemplate  )
    {
        $id = $request->get('id');
        if ($id>0)
            $idrecurso = $id;

        if ($idrecurso > 0)
        {
               $task = $this->getDoctrine()
                    ->getRepository($entity)
                    ->find($idrecurso); 
        }

        $form = $this->createForm($formTemplate, $task);
        $form->handleRequest( $request );

        if ( $request->isMethod( 'POST' ) ) {
          return $this->saveData($form);
        }

        $view = $this->view($form, 200)
            ->setTemplate($twigTemplate)
            ->setTemplateVar('generico')
            ->setTemplateData(array(
            'idRecurso' => $idrecurso
             ));

        return $this->handleView($view);

        
/*        return $this->render($twigTemplate, array(
          'form' => $form->createView(),
          'idRecurso' => $idrecurso
      ));*/

    }


    
 }
