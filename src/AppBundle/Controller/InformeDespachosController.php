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
use PHPExcel_Style_Fill;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Description of ServiciosController
 *
 * @author Jose
 */
class InformeDespachosController extends BasicController {


        /** Devuelve el objeto en una página Ajax
     * @Route("/informeDespachos", name="informeDespachos")
     */
    public function InformeAction(Request $request, $idrecurso = 0)
    {
        $task = new ServicioPaciente();
        $formTemplate = new \AppBundle\Form\InformeDespachosForm();

        $form = $this->createForm($formTemplate, $task);

        if ( $request->isMethod( 'POST' ) ) {
            
             $form->handleRequest( $request );
             $fechaDesde = $form->get('desde')->getData();
             $fechaHasta = $form->get('hasta')->getData();
             $tipoInforme = $form->get('tipoInforme')->getData();
             
            // Actualizo los pacientes con lo que tenga en memoria
            if ($tipoInforme == 'D')
            {
              return $this->generateStatisticsDiagnosis($fechaDesde, $fechaHasta);
            }
            else if ($tipoInforme == 'O')
            {
                return $this->generateOficios($fechaDesde, $fechaHasta);
            }
            else 
            {
                return $this->generateStatisticsGeneral($fechaDesde, $fechaHasta);
            }
             
        }
        else
        {
            $form = $this->createForm($formTemplate, $task);
            $view = $this->view($form, 200)
                ->setTemplate("informe/informeDespachos.html.twig")
                ->setTemplateVar('generico')
                ->setTemplateData(array(
                'idRecurso' => $idrecurso
                 ));

            return $this->handleView($view);

        }            
        
        
    }


     
     /**
 * Genera el excel con las estatisticas de traslados

 * @param type $fechaDesde
 * @param type $fechaHasta
 * @return StreamedResponse
 */
    public function generateStatisticsDiagnosis($fechaDesde, $fechaHasta){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta){
 
    $fileType = 'Excel2007';
    $fileName = 'template/informeEstadisticoDiagnosticos.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Informe estadístico ") // Titulo
            ->setSubject("Reporte estadístico despachos") //Asunto
            ->setDescription("Informe estadistico despachos") //Descripción
            ->setKeywords("reporte servicio despachos") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       
// ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('I3',  $fechaHasta)
               ;  
       
        $emConfig = $this->getDoctrine()->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
    
        

        $this->rellenarEstadisticasDiagnosticos($objPHPExcel, $fechaDesde, $fechaHasta);
    
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');


    });
 
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition','attachment; filename="informeEstadisticoDespachos.xlsx"');
 
    return $response;
}

/**
 *  Rellena el excel con las estadísticas de origen
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $diagnostico
 */
public function rellenarEstadisticasDiagnosticos($objPHPExcel, $fechaDesde, $fechaHasta)
{
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('AppBundle:Diagnostico', 'd', 'WITH', 'p.diagnostico1 = d.id')
            ->select(' d.descripcion, d.identificador, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->groupby('d.identificador, d.descripcion, gMes')
            ->orderby('d.identificador, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $datoAnterior = null;
        $filaServicio = 1;
        

        foreach($datos as $datoMes)
        {
            
            if ($datoAnterior && $datoMes["identificador"] != $datoAnterior["identificador"]) {
                $filaServicio++;
            }
            $this->rellenarDato($objPHPExcel, 0, $datoMes, $filaServicio);
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
            ->setCellValue("A".$filaExcel, $dato["descripcion"])
            ->setCellValue("B".$filaExcel, $dato["identificador"])
            ->setCellValue($columnaMes.$filaExcel, $dato["total"])

                ;
}


/**
 * Genera el excel con la planilla de servicios

 * @param type $fechaDesde
 * @param type $fechaHasta
 * @return StreamedResponse
 */
    public function generateOficios($fechaDesde, $fechaHasta){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta){
 
    $fileType = 'Excel2007';
    $fileName = 'template/informeOficios.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Informe oficios") // Titulo
            ->setSubject("Reporte oficios") //Asunto
            ->setDescription("Informe oficios") //Descripción
            ->setKeywords("reporte servicio oficios") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       // ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('I3',  $fechaHasta)
               ;  

       
        $filaServicio = 6;
        
        $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->andwhere('s.causa IS NOT NULL')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->orderBy('s.fecha', 'ASC');
        
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
    $response->headers->set('Content-Disposition','attachment; filename="informeOficios.xlsx"');
 
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
 */
public function rellenarFila($objPHPExcel, $servicio, $paciente, $filaServicio)
{
    $columna='A';

    // relleno los datos del paciente en la fila que corresponde
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  $servicio->getTribunal())
            ->setCellValue($columna++.$filaServicio,  $servicio->getCaratula())
            ->setCellValue($columna++.$filaServicio,  $servicio->getCausa());
                
        $objPHPExcel->setActiveSheetIndex(0)        
            ->setCellValue($columna++.$filaServicio,  $paciente->getApellido())
            ->setCellValue($columna++.$filaServicio,  $paciente->getNombre())
            ->setCellValue($columna++.$filaServicio,  $paciente->getEdad() . $paciente->getTipoEdad())
            ->setCellValue($columna++.$filaServicio,  $paciente->getDni());

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  $servicio->getCalle() ." ". $servicio->getNro())
            ->setCellValue($columna++.$filaServicio,  ($servicio->getLocalidad())? $servicio->getLocalidad()->getName(): "")                        
            ->setCellValue($columna++.$filaServicio,  $servicio->getTelefono())
            ;
            
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna++.$filaServicio,  ($servicio->getCentroAtencionTraslado())? $servicio->getCentroAtencionTraslado()->getDescripcion(): "")
            ->setCellValue($columna++.$filaServicio,  $servicio->getObservaciones())
            ->setCellValue($columna++.$filaServicio,  $servicio->getFecha()->format('d-m-y'))
         ;
}
 


     /**
 * Genera el excel con las estatisticas de traslados

 * @param type $fechaDesde
 * @param type $fechaHasta
 * @return StreamedResponse
 */
    public function generateStatisticsGeneral($fechaDesde, $fechaHasta){

    $response = new StreamedResponse();
    $response->setCallback(function() use ($fechaDesde, $fechaHasta){
 
    $fileType = 'Excel2007';
    $fileName = 'template/informeEstadisticoGeneral.xlsx';
        //$objPHPExcel = new PHPExcel();
        
       $objReader = PHPExcel_IOFactory::createReader($fileType);
       $objPHPExcel = $objReader->load($fileName);
       
       $objPHPExcel->getProperties()->setCreator("MAB") // Nombre del autor
            ->setLastModifiedBy("MAB") //Ultimo usuario que lo modificó
            ->setTitle("Informe estadístico ") // Titulo
            ->setSubject("Reporte estadístico general") //Asunto
            ->setDescription("Informe estadistico general") //Descripción
            ->setKeywords("reporte servicio general") //Etiquetas
            ->setCategory("Reporte excel"); //Categoria
        
       
// ponemos los datos generales del informe 
       $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E3',  $fechaDesde)  
                ->setCellValue('I3',  $fechaHasta)
               ;  
       
        $emConfig = $this->getDoctrine()->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
    
        

        $this->rellenarEstadisticasGenerales($objPHPExcel, $fechaDesde, $fechaHasta);
    
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');


    });
 
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition','attachment; filename="informeEstadisticoDespachos.xlsx"');
 
    return $response;
}

/**
 *  Rellena el excel con las estadísticas de origen
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $diagnostico
 */
public function rellenarEstadisticasGenerales($objPHPExcel, $fechaDesde, $fechaHasta)
{
        $filaServicio = 6;
        $this->rellenarEstadisticasGeneralesCodigo($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
        $this->rellenarEstadisticasGeneralesSexo($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
        $this->rellenarEstadisticasGeneralesLocalidades($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
        $this->rellenarEstadisticasGeneralesLlamadas($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
        $this->rellenarEstadisticasGeneralesMedicos($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
        $this->rellenarEstadisticasGeneralesOtros($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
        

        
}

/**
 * Estadísticas por código
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $filaServicio
 */
public function rellenarEstadisticasGeneralesCodigo($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->leftJoin('AppBundle:Motivo', 'm', 'WITH', 's.motivo = m.id')
            ->select(' substring(m.codigo, 1, 2) AS cod, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, 'CÓDIGOS', array(array('/01 /','/02 /','/03 /','/04 /'),array('ROJO','AMARILLO','VERDE','AZUL')));
}

/**
 * Estadísticas por sexo
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $filaServicio
 */
public function rellenarEstadisticasGeneralesSexo($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
    
    
    
    $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->select(' p.sexo AS cod, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
    $query = $queryBuilder->getQuery();
    $datos = $query->getResult();

    $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, 'SEXO', array(array('/F /','/M /'),array('FEMENINO','MASCULINO')));

}

/**
 * Estadísticas por localidades
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $filaServicio
 */
public function rellenarEstadisticasGeneralesLocalidades($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->leftJoin('AppBundle:Localidad', 'l', 'WITH', 's.localidad = l.id')
            ->select(' l.name AS cod, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, 'LOCALIDADES', null);
}

/**
 * Estadísticas por llamadas
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $filaServicio
 */
public function rellenarEstadisticasGeneralesLlamadas($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->leftJoin('AppBundle:IngresoLlamado', 'i', 'WITH', 's.ingresoLlamado = i.id')
            ->select(' i.name AS cod, MONTH(s.fecha) AS gMes, count(p) as total')
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, 'LLAMADAS', null);
}


/**
 * Estadísticas por medicos que están dentro del móvil lógico
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $filaServicio
 */
public function rellenarEstadisticasGeneralesMedicos($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('AppBundle:GrupoRecurso', 'g', 'WITH', 's.movillogico = g.id')
            ->innerJoin('g.personas', 'r')
            ->innerJoin('r.claserecurso','c','WITH','c.descripcion = :claseRecurso')
            ->select(" concat(r.apellido, concat(', ', r.nombre)) AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('claseRecurso', 'Médico')  // TODO, configurable
            ->setParameter('tipo', 'D')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, 'MÉDICOS', null);
}

/**
 * Estadísticas otros
 * @param type $objPHPExcel
 * @param type $fechaDesde
 * @param type $fechaHasta
 * @param type $filaServicio
 */
public function rellenarEstadisticasGeneralesOtros($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $this->rellenarEstadisticasGeneralesPrevenciones($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
    $this->rellenarEstadisticasGeneralesAuxiliosAnulados($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
    $this->rellenarEstadisticasGeneralesPacientesAusentes($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
    $this->rellenarEstadisticasGeneralesSinDiagnostico($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
    $this->rellenarEstadisticasGeneralesAuxiliosRealizados($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);
    $this->rellenarEstadisticasGeneralesTotalAuxilios($objPHPExcel, $fechaDesde, $fechaHasta, $filaServicio);

    
    
}


public function rellenarEstadisticasGeneralesPrevenciones($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->select("'PREVENCIONES' AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->andwhere('s.cobertura= :cobertura')

            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->setParameter('cobertura', 'S')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, 'OTROS', null);
}

public function rellenarEstadisticasGeneralesAuxiliosAnulados($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('p.diagnostico1', 'd')

            ->select("'AUXILIOS ANULADOS' AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->andwhere('d.identificador= :identificador')

            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->setParameter('identificador', 'S1')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, null, null);
}

public function rellenarEstadisticasGeneralesPacientesAusentes($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        
        
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('p.diagnostico1', 'd')

            ->select("'PACIENTES AUSENTES' AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->andwhere('d.identificador= :identificador')

            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->setParameter('identificador', 'S2')
            ->groupby('cod, gMes')
            ->orderby('cod, gMes')
                    ;
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, null, null);
}

public function rellenarEstadisticasGeneralesAuxiliosRealizados($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')
            ->innerJoin('p.diagnostico1', 'd')

            ->select("'AUXILIOS REALIZADOS' AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->andwhere('d.identificador<>:identificador AND d.identificador<>:identificador2')
            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')
            ->setParameter('identificador', 'S1')
            ->setParameter('identificador2', 'S2')

            ->groupby('cod, gMes')
            ->orderby('cod, gMes');
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, null, null);
}


public function rellenarEstadisticasGeneralesSinDiagnostico($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')

            ->select("'SIN DIAGNOSTICO' AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')
            ->andwhere('p.diagnostico1 IS NOT NULL')

            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')

            ->groupby('cod, gMes')
            ->orderby('cod, gMes');
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, null, null);
}

public function rellenarEstadisticasGeneralesTotalAuxilios($objPHPExcel, $fechaDesde, $fechaHasta, &$filaServicio)
{
    $repository = $this->getDoctrine()->getEntityManager()
                    ->getRepository('AppBundle:ServicioPaciente');
        
        $queryBuilder = $repository->createQueryBuilder('p')
            ->innerJoin('AppBundle:Servicio', 's', 'WITH', 'p.servicio = s.id')

            ->select("'TOTAL DE AUXILIOS' AS cod, MONTH(s.fecha) AS gMes, count(p) as total")
            ->where('s.fecha >= :desde')
            ->andwhere('s.fecha < :hasta')
            ->andwhere('s.tipoServicio = :tipo')

            ->setParameter('desde', $fechaDesde)
            ->setParameter('hasta', $fechaHasta)
            ->setParameter('tipo', 'D')

            ->groupby('cod, gMes')
            ->orderby('cod, gMes');
        
        $query = $queryBuilder->getQuery();
        $datos = $query->getResult();
        
        $this->rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, $filaServicio, null, null);
}


/**
 * Función para rellenar cada una de las celdas con la suma que se ha encontrado anteriormiente dependiendo del filtro, la columna inicial debe contener COD
 * @param type $objPHPExcel
 * @param type $datos
 * @param type $filaServicio
 * @param type $titulo
 */
public function rellenarEstadisticasGeneralesDatos($objPHPExcel, $datos, &$filaServicio, $titulo, $arrayReplace)
{
    if ($titulo) {    
        $this->ponerEncabezadoStatistics($objPHPExcel, $filaServicio, $titulo);
        $filaServicio++;
    }

    $datoAnterior = null;

    foreach($datos as $datoMes)
    {

        if ($datoAnterior && $datoMes["cod"] != $datoAnterior["cod"]) {
            $filaServicio++;
        }
        $this->rellenarDatoGeneral($objPHPExcel, 0, $datoMes, $filaServicio, $arrayReplace);
        $datoAnterior = $datoMes;

    }

    $filaServicio++;
}



/**
 * Rellena una fila del excel con los datos del servicio y del paciente
 * @param type $servicio
 * @param type $paciente
 * @param type $filaServicio
 */
public function rellenarDatoGeneral($objPHPExcel,$hoja, $dato, $filaExcel, $arrayReplace)
{
    $columna='A';
    $mes = $dato["gMes"];
    $columnaMesNum = ord($columna) + $mes;
    $columnaMes = chr($columnaMesNum);
    
    if ($dato["cod"] == null)
    {
        $dato["cod"] = "SIN ESPECIFICAR";
    }
    else if ($arrayReplace)
    {
        $aux = preg_replace($arrayReplace[0], $arrayReplace[1], $dato["cod"]." ", 1);
        $dato["cod"] = $aux;
    }
    // relleno los datos del paciente en la fila que corresponde
        $objPHPExcel->setActiveSheetIndex($hoja)
            ->setCellValue("A".$filaExcel, $dato["cod"])
            ->setCellValue($columnaMes.$filaExcel, $dato["total"])

                ;
}


private function ponerEncabezadoStatistics($objPHPExcel, $fila, $titulo)
{
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".$fila, $titulo)
            ->setCellValue("N".$fila, '');

        $this->cellColor($objPHPExcel, 'A'.$fila.':N'.$fila, 'CEE3F6');
    
}


private function cellColor($objPHPExcel, $cells,$color){

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

         
}
    
