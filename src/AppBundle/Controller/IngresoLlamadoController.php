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
use AppBundle\Entity\IngresoLlamado;
use APY\DataGridBundle\Grid\Source\Entity;


/**
 * Description of IngresoLlamadoController
 *
 * @author Jose
 */
class IngresoLlamadoController extends BasicController {
           /**
     * @Route("/ingresollamado", name="ingresollamadoedit")
     * @Route("/ingresollamado/{idrecurso}", name="ingresollamadoeditparams")
     */
    public function IngresoLlamadoAction(Request $request, $idrecurso = 0)
    {
        $task = new IngresoLlamado();
        $route = "ingresollamadoeditparams";
        $entity = "AppBundle:IngresoLlamado";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);

    }
    
    
    /** Borra el objeto
     * @Route("/ingresollamadodelete", name="ingresollamadodelete")
     * @Route("/ingresollamadodelete/{idrecurso}", name="ingresollamadodeleteParams")
     */
    public function ingresollamadoDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new IngresoLlamado();
        return $this->deleteRecurso($request, $task, "AppBundle:IngresoLlamado", $idrecurso);
    }

    
    
    /**
     * @Route("/ingresollamadogrid", name="ingresollamadogrid")
     */
    public function ingresollamadoGridAction()
    {
        $source = new Entity('AppBundle:IngresoLlamado');
            
        return $this->showBasicGrid($source,"ingresollamadoedit", "ingresollamadodelete", "Ingreso Llamado", "Ingresos Llamado");
    }

}
