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
use AppBundle\Entity\DemoraDespacho;
use APY\DataGridBundle\Grid\Source\Entity;


/**
 * Description of EstadoTablaController
 *
 * @author Jose
 */
class DemoraDespachoController extends BasicController {
           /**
     * @Route("/demoradespacho", name="demoradespachoedit")
     * @Route("/demoradespacho/{idrecurso}", name="demoradespachoeditparams")
     */
    public function demoradespachoAction(Request $request, $idrecurso = 0)
    {
        $task = new DemoraDespacho();
        $route = "demoradespachoeditparams";
        $entity = "AppBundle:DemoraDespacho";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);

    }
    
    
    /** Borra el objeto
     * @Route("/demoradespachodelete", name="demoradespachodelete")
     * @Route("/demoradespachodelete/{idrecurso}", name="demoradespachodeleteParams")
     */
    public function demoradespachoDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new DemoraDespacho();
        return $this->deleteRecurso($request, $task, "AppBundle:DemoraDespacho", $idrecurso);
    }

    
    
    /**
     * @Route("/demoradespachogrid", name="demoradespachogrid")
     */
    public function demoradespachoGridAction()
    {
        $source = new Entity('AppBundle:DemoraDespacho');
            
        return $this->showBasicGrid($source,"demoradespachoedit", "demoradespachodelete", "Demora Despacho", "Demoras Despacho");
    }

}
