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
use AppBundle\Entity\EstadoTabla;
use APY\DataGridBundle\Grid\Source\Entity;


/**
 * Description of EstadoTablaController
 *
 * @author Jose
 */
class EstadoTablaController extends BasicController {
           /**
     * @Route("/estadotabla", name="estadotablaedit")
     * @Route("/estadotabla/{idrecurso}", name="estadotablaeditparams")
     */
    public function EstadoTablaAction(Request $request, $idrecurso = 0)
    {
        $task = new EstadoTabla();
        $route = "estadotablaeditparams";
        $entity = "AppBundle:EstadoTabla";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);

        
/*        $task = new EstadoTabla();

        if ($idrecurso > 0)
        {
               $task = $this->getDoctrine()
                    ->getRepository('AppBundle:EstadoTabla')
                    ->find($idrecurso); 
        }
            

        return $this->ShowBasicEntity($request, $task);*/
    }
    
    
    /** Borra el objeto
     * @Route("/estadotabladelete", name="estadotabladelete")
     * @Route("/estadotabladelete/{idrecurso}", name="estadotabladeleteParams")
     */
    public function estadoTablaDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new EstadoTabla();
        return $this->deleteRecurso($request, $task, "AppBundle:EstadoTabla", $idrecurso);
    }

    
    
    /**
     * @Route("/estadotablagrid", name="estadotablagrid")
     */
    public function estadoTablaGridAction()
    {
        $source = new Entity('AppBundle:EstadoTabla');
            
        return $this->showBasicGrid($source,"estadotablaedit", "estadotabladelete", "Estado Tabla", "Estados");
    }

}
