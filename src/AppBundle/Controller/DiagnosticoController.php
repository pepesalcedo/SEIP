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
use AppBundle\Entity\Diagnostico;
use AppBundle\Form\DiagnosticoForm;
use APY\DataGridBundle\Grid\Source\Entity;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Description of DiagnosticoController
 *
 * @author Jose
 */
class DiagnosticoController extends BasicController {

    /** Devuelve el objeto en una página Ajax
     * @Route("/diagnostico", name="diagnosticoedit")
     * @Route("/diagnostico/{idrecurso}", name="diagnosticosubmitupdate")
     */
    public function diagnosticoAction(Request $request, $idrecurso = 0)
    {
        $task = new Diagnostico();
        $formTemplate = new DiagnosticoForm();


        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:Diagnostico", $task, $formTemplate, "diagnostico/diagnostico.html.twig"  );   
    
    }
    
    
    /** Borra el objeto
     * @Route("/diagnosticodelete", name="diagnosticodelete")
     * @Route("/diagnosticodelete/{idrecurso}", name="diagnosticodeleteParams")
     */
    public function diagnosticoDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new Diagnostico();
        
        return $this->deleteRecurso($request,$task,  "AppBundle:Diagnostico", $idrecurso);

    }

    /**
     * @Route("/diagnosticogrid", name="diagnosticogrid")
     */
    public function diagnosticoGridAction()
    {
        $source = new Entity('AppBundle:Diagnostico');
            
        return $this->showBasicGrid($source, "diagnosticoedit", "diagnosticodelete", "Diagnóstico", "Diagnósticos");
    }
    

 
}
