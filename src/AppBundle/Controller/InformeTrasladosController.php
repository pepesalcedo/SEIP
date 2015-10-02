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
use Doctrine\ORM\Query\Expr\Join;

/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class InformeTrasladosController extends BasicController {


        /** Devuelve el objeto en una página Ajax
     * @Route("/informeTraslados", name="informeTraslados")
     */
    public function InformeAction(Request $request, $idrecurso = 0)
    {
        $task = new ServicioPaciente();
        $formTemplate = new \AppBundle\Form\InformeTrasladosForm();

        $form = $this->createForm($formTemplate, $task);

        if ( $request->isMethod( 'POST' ) ) {
            
             $form->handleRequest( $request );
             $fechaDesde = $form->get('desde')->getData();
             $fechaHasta = $form->get('hasta')->getData();
             $tipoInforme = $form->get('tipoInforme')->getData();
             
             $diagnostico = $form->getData()->getDiagnostico1();
            // Actualizo los pacientes con lo que tenga en memoria
            if ($tipoInforme == 'D')
            {
              return $this->generateExcel($fechaDesde, $fechaHasta, $diagnostico);
            }
            else
            {
                return $this->generateStatistics($fechaDesde, $fechaHasta, $diagnostico);
            }
             
        }
        else
        {
            $form = $this->createForm($formTemplate, $task);
            $view = $this->view($form, 200)
                ->setTemplate("informe/informeTraslados.html.twig")
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
    public function generateExcel($fechaDesde, $fechaHasta, $diagnostico){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta, $diagnostico){
 
    $fileType = 'Excel2007';
    $fileName = 'template/informeTraslados.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Informe traslados") // Titulo
            ->setSubject("Reporte traslados") //Asunto
            ->setDescription("Informe traslados") //Descripción
            ->setKeywords("reporte servicio traslados") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       $diagnosticoName = ($diagnostico)? $diagnostico->getDescripcion() : "Todos";
       // ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F3',  $fechaDesde)  
                ->setCellValue('J3',  $fechaHasta)
                ->setCellValue('H2',  "Diagnostico: " . $diagnosticoName);
               ;  

       
        $filaServicio = 6;
        
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'T')
            ->orderBy('s.fecha', 'ASC');
        
        if ($diagnostico)
        {
            $queryBuilder = $queryBuilder
                    ->andwhere('p.diagnostico1 = :diagnosticoId')
                    ->setParameter('diagnosticoId', $diagnostico->getId())
                        ;
        }
        $query = $queryBuilder->getQuery();
 
        $pacientes = $query->getResult();
        
        foreach($pacientes as $paciente)
        {
            $this->rellenarPaciente($objPHPExcel, $paciente, $filaServicio);
            
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');


    });
 
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition','attachment; filename="informeTraslados.xlsx"');
 
    return $response;
}


/**
 *  Rellena el excel con los servicios y pacientes, a partir de la fila especificada
 * @param type $servicio
 * @param type $filaServicio
 */
public function rellenarPaciente($objPHPExcel, $paciente, &$filaServicio)
{
//    $pacientes = $servicio->getPacientes();

        //$numero = $servicio->getNumero();
    $this->rellenarFila($objPHPExcel, $paciente->getServicio(), $paciente, $filaServicio);
    $filaServicio++;                    

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
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('d'))
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('m'))
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('y'));
                
        $tipoEdad = ($paciente->getTipoEdad() == "A")? 'años' : (($paciente->getTipoEdad() == 'M')? 'meses' : (($paciente->getTipoEdad() == 'D')? 'dias' : "")); 
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  $paciente->getEdad())
            ->setCellValue($columna++.$filaServicio,  $tipoEdad);

        $objPHPExcel->setActiveSheetIndex(0)        
            ->setCellValue($columna++.$filaServicio,  $paciente->getApellido())
            ->setCellValue($columna++.$filaServicio,  $paciente->getNombre())
            ->setCellValue($columna++.$filaServicio,  $paciente->getDni())
                    ;
        $diagnosticos = $paciente->getDiagnosticos();
        $objPHPExcel->setActiveSheetIndex(0)        
            ->setCellValue($columna++.$filaServicio,  $diagnosticos);

        
        $ubicacion = ($servicio->getCentroAtencion())? $servicio->getCentroAtencion()->getTipo(): null;
        
        if ($ubicacion && $ubicacion->isCentro())
        {
            $objPHPExcel->setActiveSheetIndex(0)
               ->setCellValue($columna++.$filaServicio,  $servicio->getCentroAtencion()->getDescripcion());            
        }
        else
        {
            $localidad = ($servicio->getLocalidad())? $servicio->getLocalidad()->getName():"";
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($columna++.$filaServicio,  $servicio->getCalle() ." ". $servicio->getNro() . " - ". $localidad);
            
        }
            
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  ($servicio->getCentroAtencionTraslado())? $servicio->getCentroAtencionTraslado()->getDescripcion(): "")
            ->setCellValue($columna++.$filaServicio,  $servicio->getMedicoSolicita())
            ->setCellValue($columna++.$filaServicio,  $servicio->getMedicoRecibe())

         ;
     }
     
     
     /**
 * Genera el excel con las estatisticas de traslados

 * @param type $fechaDesde
 * @param type $fechaHasta
 * @return StreamedResponse
 */
    public function generateStatistics($fechaDesde, $fechaHasta, $diagnostico){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta, $diagnostico){
 
    $fileType = 'Excel2007';
    $fileName = 'template/informeEstadisticoTraslados.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Informe estadístico traslados") // Titulo
            ->setSubject("Reporte estadístico traslados") //Asunto
            ->setDescription("Informe estadistico traslados") //Descripción
            ->setKeywords("reporte servicio traslados") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       $diagnosticoName = ($diagnostico)? $diagnostico->getDescripcion() : "Todos";
       
// ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('I3',  $fechaHasta)
                ->setCellValue('H2',  "Diagnostico: " . $diagnosticoName);
               ;  
       
       $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('I3',  $fechaHasta)
                ->setCellValue('H2',  "Diagnostico: " . $diagnosticoName);
               ;  

               $emConfig = $this->getDoctrine()->getEntityManager()->getConfiguration();
            $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
            $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
            $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
    
        

        $this->rellenarEstadisticasOrigenes($objPHPExcel, $fechaDesde, $fechaHasta, $diagnostico);
        $this->rellenarEstadisticasDestino($objPHPExcel, $fechaDesde, $fechaHasta, $diagnostico);
    
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');


    });
 
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition','attachment; filename="informeEstadisticoTraslados.xlsx"');
 
    return $response;
}

/**
 *  Rellena el excel con las estadísticas de origen
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $diagnostico
 */
public function rellenarEstadisticasOrigenes($objPHPExcel, $fechaDesde, $fechaHasta, $diagnostico)
{
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('AppBundle:CentroAtencion', 'c', 'WITH', 's.centroAtencion = c.id')
            ->select(' c.descripcion, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'T')
            ->groupby('c.descripcion, gMes')
            ->orderby('c.descripcion, gMes')
                    ;
        
        if ($diagnostico)
        {
            $queryBuilder = $queryBuilder
                    ->andwhere('p.diagnostico1 = :diagnosticoId')
                    ->setParameter('diagnosticoId', $diagnostico->getId())
                        ;
        }

        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $datoAnterior = null;
        $filaServicio = 1;
        

        foreach($datos as $datoMes)
        {
            
            if ($datoAnterior && $datoMes["descripcion"] != $datoAnterior["descripcion"])
                $filaServicio++;
            $this->rellenarDato($objPHPExcel, 0, $datoMes, $filaServicio);
            $datoAnterior = $datoMes;
            
        }

}

/**
 *  Rellena el excel con las estadísticas de origen
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $diagnostico
 */
public function rellenarEstadisticasDestino($objPHPExcel, $fechaDesde, $fechaHasta, $diagnostico)
{
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('AppBundle:CentroAtencion', 'c', 'WITH', 's.centroAtencionTraslado = c.id')
            ->select(' c.descripcion, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'T')
            ->groupby('c.descripcion, gMes')
            ->orderby('c.descripcion, gMes')
                    ;
        
        if ($diagnostico)
        {
            $queryBuilder = $queryBuilder
                    ->andwhere('p.diagnostico1 = :diagnosticoId')
                    ->setParameter('diagnosticoId', $diagnostico->getId())
                        ;
        }

        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $datoAnterior = null;
        $filaServicio = 1;
        

        foreach($datos as $datoMes)
        {
            
            if ($datoAnterior && $datoMes["descripcion"] != $datoAnterior["descripcion"])
                $filaServicio++;
            $this->rellenarDato($objPHPExcel, 1, $datoMes, $filaServicio);
            $datoAnterior = $datoMes;
            
        }

}


/**
 * Rellena una fila del excel con los datos del servicio y del paciente
 * @param type $servicio
 * @param type $paciente
 * @param type $filaServicio
 */
public function rellenarDato($objPHPExcel,$hoja, $dato, $filaServicio)
{
    $columna='A';
    $filaExcel = $filaServicio + 5;
    $mes = $dato["gMes"];
    $columnaMesNum = ord($columna) + $mes + 1;
    $columnaMes = chr($columnaMesNum);
    // relleno los datos del paciente en la fila que corresponde
        $objPHPExcel->setActiveSheetIndex($hoja)
            ->setCellValue("A".$filaExcel, $filaServicio)
            ->setCellValue("B".$filaExcel, $dato["descripcion"])
            ->setCellValue($columnaMes.$filaExcel, $dato["total"])

                ;
}



         
}
    
