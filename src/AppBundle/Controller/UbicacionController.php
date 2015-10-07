<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ubicacion;
use AppBundle\Form\UbicacionForm;
use APY\DataGridBundle\Grid\Source\Entity;

/**
 * Description of DiagnosticoController
 *
 * @author Jose
 */
class UbicacionController extends BasicController {

    /** Devuelve el objeto en una página Ajax
     * @Route("/ubicacion", name="ubicacionedit")
     * @Route("/ubicacion/{idrecurso}", name="ubicacionsubmitupdate")
     */
    public function ubicacionAction(Request $request, $idrecurso = 0)
    {
        $task = new Ubicacion();
        $formTemplate = new UbicacionForm();


        return $this->editClaseRecurso($request, $idrecurso, "AppBundle:Ubicacion", $task, $formTemplate, "servicio/ubicacion.html.twig"  );   
    
    }
    
    
    /** Borra el objeto
     * @Route("/ubicaciondelete", name="ubicaciondelete")
     * @Route("/ubicaciondelete/{idrecurso}", name="ubicaciondeleteParams")
     */
    public function ubicacionDeleteAction(Request $request, $idrecurso = 0)
    {
        $task = new Ubicacion();
        
        return $this->deleteRecurso($request,$task,  "AppBundle:Ubicacion", $idrecurso);

    }

    /**
     * @Route("/ubicaciongrid", name="ubicaciongrid")
     */
    public function ubicacionGridAction()
    {
        $source = new Entity('AppBundle:Ubicacion');
            
        return $this->showBasicGrid($source, "ubicacionedit", "ubicaciondelete", "Ubicación", "Ubicaciones");
    }
    

 
}
