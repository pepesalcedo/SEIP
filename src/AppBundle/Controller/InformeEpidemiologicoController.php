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
use AppBundle\Entity\Servicio;
use AppBundle\Form\PlanillaServiciosForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class InformeEpidemiologicoController extends BasicController {


        /** Devuelve el objeto en una página Ajax
     * @Route("/informeEpidemiologico", name="informeEpidemiologico")
     */
    public function InformeAction(Request $request, $idrecurso = 0)
    {
        $task = new ServicioPaciente();
        $formTemplate = new \AppBundle\Form\InformeEpidemiologicoForm();

        $form = $this->createForm($formTemplate, $task);

        if ( $request->isMethod( 'POST' ) ) {
            
             $form->handleRequest( $request );
             $fechaDesde = $form->get('desde')->getData();
             $fechaHasta = $form->get('hasta')->getData();
            // Actualizo los pacientes con lo que tenga en memoria
            return $this->generateExcel($fechaDesde, $fechaHasta);
        }
        else
        {
            $form = $this->createForm($formTemplate, $task);
            $view = $this->view($form, 200)
                ->setTemplate("informe/informeEpidemiologico.html.twig")
                ->setTemplateVar('generico')
                ->setTemplateData(array(
                'idRecurso' => $idrecurso
                 ));

            return $this->handleView($view);

        }            
        
        
    }


/**
 * Genera el excel con la planilla de servicios

 * @param type $fechaDesde
 * @param type $fechaHasta
 * @return StreamedResponse
 */
    public function generateExcel($fechaDesde, $fechaHasta){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta){
 
    $fileType = 'Excel2007';
    $fileName = 'template/informeEpidemiologico.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Informe epidemiológico") // Titulo
            ->setSubject("Reporte epidemiológico") //Asunto
            ->setDescription("Informe epidemiológico") //Descripción
            ->setKeywords("reporte servicio epidemiológico") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       // ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('J3',  $fechaHasta)
                ;  

       
        $filaServicio = 3;
        
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:Servicio');
        
        $query = $repository->createQueryBuilder('s')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->orderBy('s.fecha', 'ASC')
            ->getQuery();
 
        $servicios = $query->getResult();
        
        foreach($servicios as $servicio)
        {
            $this->rellenarServicio($objPHPExcel, $servicio, $filaServicio);
            
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');


    });
 
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition','attachment; filename="informeEpidemiologico.xlsx"');
 
    return $response;
}


/**
 *  Rellena el excel con los servicios y pacientes, a partir de la fila especificada
 * @param type $servicio
 * @param type $filaServicio
 */
public function rellenarServicio($objPHPExcel, $servicio, &$filaServicio)
{
    $pacientes = $servicio->getPacientes();

    foreach($pacientes as $paciente)
    {
        //$numero = $servicio->getNumero();
        $this->rellenarFila($objPHPExcel, $servicio, $paciente, $filaServicio);
        $filaServicio++;                    
    }

}



/**
 * Rellena una fila del excel con los datos del servicio y del paciente
 * @param type $servicio
 * @param type $paciente
 * @param type $filaServicio
 */
public function rellenarFila($objPHPExcel, $servicio, $paciente, $filaServicio)
{
    $columna='A';

    // relleno los datos del paciente en la fila que corresponde
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('d-m-y'));
        $objPHPExcel->setActiveSheetIndex(1)        
            ->setCellValue($columna++.$filaServicio,  $paciente->getApellido() . ", " . $paciente->getNombre());

        $edad = $paciente->getEdad();
        // Si es un paciente menor de un año lo pongo en la casilla correspondiente
            if ($paciente->getTipoEdad() == 'D' || $paciente->getTipoEdad() == 'M')
           {
               $columna = 'E';
               if ($paciente->getTipoEdad() == 'M') {
                   $edad = $edad * 30;
               }
           }

        // Lo pongo en la columna que toca dependiendo del sexo
            else if ($paciente->getSexo() == 'M')
            { 
               $columna++;
            }

        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($columna++.$filaServicio,  $edad);
        
        $columna = 'F';
        $objPHPExcel->setActiveSheetIndex(1)
           ->setCellValue($columna++.$filaServicio,  $servicio->getCalle() ." ". $servicio->getNro())
            ->setCellValue($columna++.$filaServicio,  ($servicio->getLocalidad())? $servicio->getLocalidad()->getName():"")
            ->setCellValue($columna++.$filaServicio,  ($servicio->getCentroAtencion())? $servicio->getCentroAtencion()->getDescripcion():"");
        $objPHPExcel->setActiveSheetIndex(1)

            ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico1())? $paciente->getDiagnostico1()->getDescripcion(): "")
            ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico1())? $paciente->getDiagnostico1()->getIdentificador(): "")
            ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico2())? $paciente->getDiagnostico2()->getIdentificador(): "")
            ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico3())? $paciente->getDiagnostico3()->getIdentificador(): "")
            ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico4())? $paciente->getDiagnostico4()->getIdentificador(): "")
            ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico5())? $paciente->getDiagnostico5()->getIdentificador(): "")

            ;
        
        if ($filaServicio > 3)
        {

            $arrayInicial = array("A3","B3","C3","D3","E3","F3","G3","H3", "I3", "J3", "K3", "L3", "M3", "N3", "O3");

            
            for ($i=0; $i<6; $i++)
            {

                    $arrayFinal = array("A".$filaServicio,"B".$filaServicio,"C".$filaServicio,"D".$filaServicio,"E".$filaServicio,"F".$filaServicio,"G".$filaServicio,
                                 "H".$filaServicio, "I".$filaServicio, "J".$filaServicio, "K".$filaServicio, "L".$filaServicio, "M".$filaServicio, "N".$filaServicio, "O".$filaServicio);

                    $celda = $columna."3";
                    $formula = $objPHPExcel->getActiveSheet()->getCell($celda)->getValue();
                    $newFormula = str_replace($arrayInicial, $arrayFinal, $formula);
                    $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue($columna++.$filaServicio,  $newFormula);

            }
        }
}
         
         
}
    
