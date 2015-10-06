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
class InformePlanillaController extends BasicController {


    /** Devuelve el mapa de la calle y localidad
     * @Route("/generateCsvServicio", name="generateCsvServicio")
     */
public function generateCsvAction(){
 

    $response = new StreamedResponse();
    $response->setCallback(function(){
 
        
        
        $handle = fopen('php://output', 'w+');
 
        // Add the header of the CSV file
        fputcsv($handle, array('Código',
            'Surname', 
            'Código Motivo', 
            'Ingreso llamado'),';');
        // Query data from database
        
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:Servicio');
        
        $servicios= $repository->findAll();


        
        foreach($servicios as $servicio)
        {
            $pacientes = $servicio->getPacientes();
            fputcsv(
                $handle, // The file pointer
                array($servicio->getNumero(),
                    ($servicio->getMotivo())? $servicio->getMotivo()->getCodigo() : "", 
                    ($servicio->getIngresoLlamado())? $servicio->getIngresoLlamado()->getName() : "",
                    $servicio->getCalle()
                    ), // The fields
                ';' // The delimiter
             );
        }
 
        fclose($handle);
        
    });
 
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition','attachment; filename="prueba.csv"');
 
    return $response;
}
    

        /** Devuelve el objeto en una página Ajax
     * @Route("/planillaServicios", name="planillaServicios")
     */
    public function ServicioAction(Request $request, $idrecurso = 0)
    {
        $task = new Servicio();
        $formTemplate = new PlanillaServiciosForm();

        $form = $this->createForm($formTemplate, $task);

        if ( $request->isMethod( 'POST' ) ) {
            
             $form->handleRequest( $request );
             $fechaDesde = $form->get('desde')->getData();
             $fechaHasta = $form->get('hasta')->getData();
             $servicio = $form->getData();
            // Actualizo los pacientes con lo que tenga en memoria
            return $this->generateExcelServiciosAction($fechaDesde, $fechaHasta, $servicio->getTipoServicio());
        }
        else
        {
            $form = $this->createForm($formTemplate, $task);
            $view = $this->view($form, 200)
                ->setTemplate("informe/planillaServicio.html.twig")
                ->setTemplateVar('generico')
                ->setTemplateData(array(
                'idRecurso' => $idrecurso
                 ));

            return $this->handleView($view);

        }            
        
        
    }


/** Genera el excel con la planilla de servicios
     * @Route("/generateExcelServicio", name="generateExcelServicio")
     */
public function generateExcelServiciosAction($fechaDesde, $fechaHasta, $tipoServicio){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta, $tipoServicio){
 
    $fileType = 'Excel2007';
    $fileName = 'template/planillaServicio.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Planilla de Servicios") // Titulo
            ->setSubject("Reporte de Servicios") //Asunto
            ->setDescription("Detalle de servicios") //Descripción
            ->setKeywords("reporte servicio") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       $tipoServicioText = ($tipoServicio == 'D')? "Servicios de Despacho" : "Servicios de traslado"; 
       
       // ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('J3',  $fechaHasta)
                ->setCellValue('I2',  $tipoServicioText)
                ;  

       
        $filaServicio = 6;
        
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:Servicio');
        
        $query = $repository->createQueryBuilder('s')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', $tipoServicio)
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
    $response->headers->set('Content-Disposition','attachment; filename="planillaServicios.xlsx"');
 
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

        if ($pacientes->count() == 0)
        {
            $this->rellenarFila($objPHPExcel, $servicio->getNumero(), $servicio, null, $filaServicio);
            $filaServicio++;
        }
        else
        {
            $numeroPaciente = 1;
            foreach($pacientes as $paciente)
            {
                $numero = $servicio->getNumero();
                if ($numeroPaciente > 1)
                {
                    $numero .= "bis" . $numeroPaciente;
                }
                $this->rellenarFila($objPHPExcel, $numero, $servicio, $paciente, $filaServicio);
                $filaServicio++;                    
                $numeroPaciente++;
            }
        }
    
}



/**
 * Rellena una fila del excel con los datos del servicio y del paciente
 * @param type $servicio
 * @param type $paciente
 * @param type $filaServicio
 */
public function rellenarFila($objPHPExcel, $numero, $servicio, $paciente, $filaServicio)
{
        $columna='A';

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  $numero)  
            ->setCellValue($columna++.$filaServicio,  ($servicio->getMotivo())? $servicio->getMotivo()->getCodigo() : "")
            ->setCellValue($columna++.$filaServicio,  ($servicio->getMovillogico())? $servicio->getMovillogico()->getDescripcion() : "")
            ->setCellValue($columna++.$filaServicio,  ($servicio->getIngresoLlamado())? $servicio->getIngresoLlamado()->getName() : "")
            ->setCellValue($columna++.$filaServicio,  ($servicio->getLocalidad())? $servicio->getLocalidad()->getName() : "")
            ->setCellValue($columna++.$filaServicio,  $servicio->getCalle())
            ->setCellValue($columna++.$filaServicio,  $servicio->getNro())
            ->setCellValue($columna++.$filaServicio,  $servicio->getEntrecalles())
            ->setCellValue($columna++.$filaServicio,  ($servicio->getCentroAtencion())? $servicio->getCentroAtencion()->getTipo()->getName() . " - ". $servicio->getCentroAtencion()->getDescripcion()  : "")            
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('m'))
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('d'))
            ->setCellValue($columna++.$filaServicio,  ($servicio->getHoraLlamado())? $servicio->getHoraLlamado()->format('H:i') : "")
            ->setCellValue($columna++.$filaServicio,  ($servicio->getHoraLlegadaDestino())? $servicio->getHoraLlegadaDestino()->format('H:i') : "")
            ->setCellValue($columna++.$filaServicio,  ($servicio->getHoraDisponible())? $servicio->getHoraDisponible()->format('H:i') : "")
                    ;

        // Si tengo paciente lo relleno
        if ($paciente)
        {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($columna++.$filaServicio,  $paciente->getApellido())
                ->setCellValue($columna++.$filaServicio,  $paciente->getNombre())
                ->setCellValue($columna++.$filaServicio,  $paciente->getDni())
                ->setCellValue($columna++.$filaServicio,  $paciente->getObraSocial())
                ->setCellValue($columna++.$filaServicio,  $paciente->getEdad() . $paciente->getTipoEdad())
                ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico1())? $paciente->getDiagnostico1()->getDescripcion(): "")
                ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico2())? $paciente->getDiagnostico2()->getDescripcion(): "")
                ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico3())? $paciente->getDiagnostico3()->getDescripcion(): "")
                ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico4())? $paciente->getDiagnostico4()->getDescripcion(): "")
                ->setCellValue($columna++.$filaServicio,  ($paciente->getDiagnostico5())? $paciente->getDiagnostico5()->getDescripcion(): "")

                ;
         }
         
         $columna = "Y";
         $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue($columna++.$filaServicio,  ($servicio->getDestinoFinal())? $servicio->getDestinoFinal()->getName(): "");
         $objPHPExcel->setActiveSheetIndex(0)         
             ->setCellValue($columna++.$filaServicio,  $servicio->getSector());
         $objPHPExcel->setActiveSheetIndex(0)         
             ->setCellValue($columna++.$filaServicio,  "¿profesional?") // TODO;
                 ;
        $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue($columna++.$filaServicio,  $servicio->getTelefono()) 
             ->setCellValue($columna++.$filaServicio,  "¿paramédico?") 
             ->setCellValue($columna++.$filaServicio,  ($servicio->getUsuarioAlta())? $servicio->getUsuarioAlta()->getApellido().",".$servicio->getUsuarioAlta()->getNombre():"" ) 
                ;
        $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue($columna++.$filaServicio,  $servicio->getCobertura()) 
             ->setCellValue($columna++.$filaServicio,  "¿oficio?") 
             ->setCellValue($columna++.$filaServicio,  $servicio->getBomberos()) 
             ->setCellValue($columna++.$filaServicio,  ($servicio->getCentroAtencionTraslado())? $servicio->getCentroAtencionTraslado()->getDescripcion(): "")

                 ;

        if ($paciente)
        {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($columna++.$filaServicio,  $paciente->getFR())
                ->setCellValue($columna++.$filaServicio,  $paciente->getFC())
                ->setCellValue($columna++.$filaServicio,  $paciente->getTA())
                ->setCellValue($columna++.$filaServicio,  $paciente->getPulso())
                ->setCellValue($columna++.$filaServicio,  $paciente->getTemperatura())
                ->setCellValue($columna++.$filaServicio,  $paciente->getsatO2())
                ->setCellValue($columna++.$filaServicio,  $paciente->getEmbarazo())
                ->setCellValue($columna++.$filaServicio,  $paciente->getSemanasGestacion())
                ->setCellValue($columna++.$filaServicio,  $paciente->getTrabajoParto())
                ;
         }
         
         if ($servicio->getMotivo())
         {
            $color = '000000';
            $codigo = substr($servicio->getMotivo()->getCodigo(), 0, 2);
            if ($codigo == '01')
                $color = 'FF0000';
            else if ($codigo == '02')
                $color = 'FF4500';
            else if ($codigo == '03')
                $color = '00FF00';
            else if ($codigo == '04')
                $color = '0000FF';
             
             $styleArray = array(
                'font'  => array(
                    'color' => array('rgb' => $color),
                ));
            $objPHPExcel->getActiveSheet()
                ->getStyle('A'.$filaServicio.':AQ'.$filaServicio)
                ->applyFromArray($styleArray
                );
         }
         
}
                     
        





 
   
    
    
}
