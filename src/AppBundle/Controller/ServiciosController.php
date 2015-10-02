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
use AppBundle\Entity\Servicio;
use AppBundle\Form\ServicioForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class ServiciosController extends BasicController {

        /** Devuelve el objeto en una página Ajax
     * @Route("/servicio", name="servicioedit")
     * @Route("/servicio/{idrecurso}", name="serviciosubmitupdate")
     */
    public function ServicioAction(Request $request, $idrecurso = 0)
    {
        $session = $request->getSession();
        $task = new Servicio();
        $formTemplate = new ServicioForm();

        $id = $request->get('id');
        if ($id>0)
            $idrecurso = $id;

        // Si encontramos el recurso lo cargamos
        if ($idrecurso > 0)
        {
               $task = $this->getDoctrine()
                    ->getRepository('AppBundle:Servicio')
                    ->find($idrecurso);
               
               if ($task == null)
                   return "El recurso no existe";
        }
        else {
          // sino lo encontramos, cargamos uno nuevo con el número calculado
                $repository = $this->getDoctrine()
                ->getRepository('AppBundle:Servicio');

                // Calculamos el proximo número de servicio y lo ponemos en el número
                $task->calculateNextNumber($repository);
        

        }

        if ( $request->isMethod( 'POST' ) ) {
            // Actualizo los pacientes con lo que tenga en memoria
            $this->GrabarPacientesAsociados($session, $task);
        }
        else
        {
            // fuerza la carga de la lista
            $cv = $task->getPacientes()->count();
            
            // lo guardo en memoria para ir trabajando con la lista hasta que pulsen guardar
            $session->set('servicio', $task);
        }            
        
        
        $form = $this->createForm($formTemplate, $task);
        if ( $request->isMethod( 'POST' ) ) {
          $form->handleRequest( $request );
          return $this->saveData($form);
        }

        $view = $this->view($form, 200)
            ->setTemplate("servicio/servicio.html.twig")
            ->setTemplateVar('generico')
            ->setTemplateData(array(
            'idRecurso' => $idrecurso
             ));

        return $this->handleView($view);
 
       
    }
    
    /** Borra el objeto
     * @Route("/servicioDelete", name="serviciodelete")
     * @Route("/servicioDelete/{idrecurso}", name="serviciodeleteParams")
     */
    public function ServicioDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new Servicio();

        return $this->deleteRecurso($request,$task,  "AppBundle::Servicio", $idrecurso);
    }
    
    /**
     * @Route("/serviciogrid", name="serviciogrid")
     */
    public function ServicioGridAction()
    {
        $source = new Entity('AppBundle:Servicio');
            
        return $this->showBasicGrid($source, "servicioedit", "serviciodelete", "Servicio", "Servicios");
    }
    
    
        /**
     * @Route("/serviciopacientesgridajax/{idrecurso}", name="serviciopacientesgridajax")
     */
    public function servicioPacientesGridAjaxAction(Request $request, $idrecurso)
    {

        $session = $request->getSession();
        $servicio = $session->get('servicio');
        $pacientes = $servicio->getPacientes();
        
        //$arrayPacientes = $pacientes->toArray();
        $arrayPacientes = array();
        foreach ($pacientes as $paciente)
        {
            array_push($arrayPacientes, array(
                'id' => $paciente->getId(), 
                'dni' => $paciente->getDni(), 
                'Nombre' => $paciente->getNombre(),
                'Apellido' => $paciente->getApellido()   ));
            
        }
        
        if ($pacientes->count() == 0)
        {
            array_push($arrayPacientes, array(
                'id' => 0, 
                'Paciente' => "No hay pacientes", ));
        }
        //$arrayPersonas =  $personas->toArray();
        
        
        $source = new Vector($arrayPacientes);

        $grid = $this->getGrid($source, "", "serviciodeletepaciente", array(), $idrecurso);
        $grid->hideColumns('id');
        $grid->hideFilters();
        //$grid->setDefaultFilters(array());

        // Return the response of the grid to the template
        return $grid->getGridResponse('gridGenericoAjax.html.twig',array());

          
          //return $this->showBasicAjaxGrid($source, "", "gruporecursodeletepersona", "Grupo Recurso", "Grupos Recursos", array('dni','fechaAlta', 'profesion', 'regimen', 'estado.name'));
    }
    
    /** Borra el objeto en memoria, para que al final se guarde todo junto
     * @Route("/serviciodeletepaciente", name="serviciodeletepaciente")
     * @Route("/servicioeletepaciente/{idPrincipal}/{idrecurso}", name="serviciodeletepacienteParams")
     */
    public function servicioDeletePacienteAction(Request $request, $idPrincipal = 0, $idrecurso = 0)
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
            $task = $session->get('servicio');
            if ($task != null)
            {
                $pacientes = $task->getPacientes();
                
                foreach($pacientes as $personInTask)
                {
                    if ($personInTask->getId() == $idrecurso)
                    {
                        $task->removePaciente($personInTask);
                    }
                }
            }
            
            $session->set('servicio', $task);


            $response['success'] = true;
            
            
        }else{

            $response['success'] = false;
            $response['cause'] = $form->getErrorsAsString();

        }

        return new JsonResponse( $response );


    }
    
    
    // Se añaden o quitan los recursos asociados antes de guardar
    public function GrabarPacientesAsociados($session, &$recursoToSave)
    {
            // ya hemos guardado lo principal, !!! cuidado que no es transaccional, habrá que revisarlo mejor
            // ahora guardamos las relaciones
            $recursoMemoria = $session->get('servicio');
            $em = $this->getDoctrine()->getManager();

            // borro las entidades que ya no están 
            foreach($recursoToSave->getPacientes() as $paciente)
            {
                if (!$recursoMemoria->isPacienteInGroup($paciente->getId())) {
                    $paciente = $em->merge($paciente);
                    //$paciente->setServicio(null);
                    $recursoToSave->removePaciente($paciente);
                    
                    // Lo dejo marcado para borrar
                    $em->remove($paciente);
                }
            }

            // Añado las nuevas que me encuentro en memoria
            foreach($recursoMemoria->getPacientes() as $paciente)
            {
                if (!$recursoToSave->isPacienteInGroup($paciente->getId())) {
                    $paciente = $em->merge($paciente);
                    $paciente->setServicio($recursoToSave);
                    $recursoToSave->addPaciente($paciente);
                    
// Lo marco para guardar para que se guarda con todo
                    $em->persist($paciente);
                    
                }
            }

    }
    /** Devuelve el mapa de la calle y localidad
     * @Route("/getMapa", name="getMapa")
     */
    public function getMapa(Request $request)
    {
        
        $task = new Servicio();
        $form = $this->createFormBuilder($task)
        ->add('save', 'submit', array('label' => 'Guardar'))
        ->getForm();

        
        $calle = $request->get('calle');
        $nro = $request->get('nro');
        $localidad = $request->get('localidad');
        $provincia = $request->get('provincia');
        
       $view = $this->view($form, 200)
            ->setTemplate("mapaAjax.html.twig")
            ->setTemplateVar('generico')
            ->setTemplateData(array(
            'textoBusqueda' => $calle . "+" .$nro . "+" . $localidad . "+" . $provincia . "+Argentina",
             ));

        return $this->handleView($view);

    }
    
}
