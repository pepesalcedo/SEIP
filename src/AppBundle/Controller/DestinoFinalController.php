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
use AppBundle\Entity\DestinoFinal;
use APY\DataGridBundle\Grid\Source\Entity;


/**
 * Description of EstadoTablaController
 *
 * @author Jose
 */
class DestinoFinalController extends BasicController {
           /**
     * @Route("/destinofinal", name="destinofinaledit")
     * @Route("/destinofinal/{idrecurso}", name="destinofinaleditparams")
     */
    public function destinofinalAction(Request $request, $idrecurso = 0)
    {
        $task = new DestinoFinal();
        $route = "destinofinaleditparams";
        $entity = "AppBundle:DestinoFinal";
        
        return $this->ShowBasicEntityAjax($request, $entity, $task, $route, $idrecurso);


    }
    
    
    /** Borra el objeto
     * @Route("/destinofinaldelete", name="destinofinaldelete")
     * @Route("/destinofinaldelete/{idrecurso}", name="destinofinaldeleteParams")
     */
    public function destinofinalDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new DestinoFinal();
        return $this->deleteRecurso($request, $task, "AppBundle:DestinoFinal", $idrecurso);
    }

    
    
    /**
     * @Route("/destinofinalgrid", name="destinofinalgrid")
     */
    public function destinofinalGridAction()
    {
        $source = new Entity('AppBundle:DestinoFinal');
            
        return $this->showBasicGrid($source,"destinofinaledit", "destinofinaldelete", "Destino Final", "Destinos Finales");
    }

}
