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
use AppBundle\Entity\ServicioPaciente;
use AppBundle\Form\ServicioPacienteForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;


/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class ServicioPacienteController extends BasicController {

        /** Devuelve el objeto en una página Ajax
     * @Route("/serviciopaciente", name="serviciopacienteedit")
     * @Route("/serviciopaciente/{idAdicional}/{idrecurso}", name="serviciopacientesubmitupdate2")
     * @Route("/serviciopaciente/{idAdicional}/{idrecurso}/{command}", name="serviciopacientesubmitupdate")
     */
    public function ServicioPacienteAction(Request $request,$idAdicional = 0, $idrecurso = 0, $command = 'current')
    {
        $formTemplate = new ServicioPacienteForm();

        $id = $request->get('id');
        if ($id>0) {
            $idrecurso = $id;
        }
        
        $id = $request->get('paciente_id');
        if ($id>0) {
            $idrecurso = $id;
        }
        
        // Si no me pasan el id, busco dentro de todos que sean el servicio y devuelvo el primero
        $idPrincipal = $request->get('idAdicional');

        if ($idPrincipal > 0) {
               $idAdicional = $idPrincipal;
        }
                    
        $task = $this->getPaciente($request, $idrecurso, $idAdicional, $command);
        
        $form = $this->createForm($formTemplate, $task);
        
        if ( $request->isMethod( 'POST' ) ) {
          $form->handleRequest( $request );
          if ($form->getData()->getNombre() != "" || $form->getData()->getApellido() != "") {
            $strReturn = $this->saveData($form);
          
          
            // Añadir el paciente al servicio
            $session = $request->getSession();
            $servicio = $session->get('servicio');


            $pacienteActual =$form->getData();


            // borro y añado para asegurarme de no escribir el mismo más de una vez
            $servicio->modifyOrAddPaciente($pacienteActual);
            //$servicio->deletePacienteInGroup($pacienteActual->getId());
            //$servicio->addPaciente($pacienteActual); 

            $session->set('servicio', $servicio);

            $response['success'] = true;
            $response['cause'] = 'paciente';
            $response['id'] = $pacienteActual->getId();
          }
          else
          {
            $response['success'] = true;
            $response['cause'] = 'paciente';
            $response['id'] = 0;
              
          }
          
          // Le devuelvo una respuesta concreta para que sepa que es de un paciente y no lo refresque todo
          return new JsonResponse( $response );
        }

        $view = $this->view($form, 200)
            ->setTemplate("servicio/paciente.html.twig")
            ->setTemplateVar('generico')
            ->setTemplateData(array(
            'idAdicional' => $idAdicional,    
            'idrecurso' => $idrecurso
             ));

        return $this->handleView($view);
 
    }
    
    
    public function getPaciente($request, &$idrecurso, $idPrincipal, $command)
    {
        $task = new ServicioPaciente();
        $session = $request->getSession();
        $servicio = $session->get('servicio');
        
        $em = $this->getDoctrine()->getManager();
        
        if ($idrecurso > 0)
        {
            if ($command == 'current')
            {
                $task = $this->getDoctrine()
                        ->getRepository('AppBundle:ServicioPaciente')
                        ->find($idrecurso);

            }
            else if ($command == 'next')
            {

                // Consigo el objeto de memoria y busco el siguiente o anterior paciente
                $paciente = $servicio->getNextPaciente($idrecurso);
                
                // si lo encuentro lo devuelvo, sino es un nuevo recurso
                if ($paciente != null) {
                    $task = $em->merge($paciente);
                    $idrecurso = $task->getId();
                }
                else {
                        $idrecurso = 0;
                }
            }
            else if ($command == 'previous')
            {

                // Consigo el objeto de memoria y busco el siguiente o anterior paciente
                $paciente = $servicio->getPreviousPaciente($idrecurso);
                if ($paciente != null) {
                    $task = $em->merge($paciente);
                    $idrecurso = $task->getId();
                }
                else { 
                    // si no puedo ir para atras devuelvo el paciente, siemppre que tenga id
                    if ($idrecurso > 0) {
                       $task = $this->getDoctrine()
                            ->getRepository('AppBundle:ServicioPaciente')
                            ->find($idrecurso);
                    }
                }
                
            }

        }
        else
        {
            if ($idPrincipal > 0 && $command == 'first')
           {
               $pacientes = $this->getDoctrine()
                    ->getRepository('AppBundle:ServicioPaciente')->findBy(array('servicio' => $idPrincipal));
                       
               // Devuelvo el primero de la lista
               if (count($pacientes) > 0) {
                 $task = $pacientes[0];
                 $idrecurso = $task->getId();
               }
           }
           else if ($command == 'previous')
           {
                // si el recurso es 0, quiere decir que es nuevo o que estoy al final, busco en memoria el último paciente y lo devuelvo
                $paciente = $servicio->getPacientes()->last();
                if ($paciente != null)
                {
                    $task = $em->merge($paciente);
                    $idrecurso = $task->getId();
                }
               
           }
           
        }
        
        return $task;

    }
    
    
    
        
}
