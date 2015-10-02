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
use AppBundle\Entity\Motivo;
use APY\DataGridBundle\Grid\Source\Entity;
use AppBundle\Form\MotivoForm;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Description of EstadoTablaController
 *
 * @author Jose
 */
class MotivoController extends BasicController {
           /**
     * @Route("/motivo", name="motivoedit")
     * @Route("/motivo/{idrecurso}", name="motivoeditparams")
     */
    public function MotivoAction(Request $request, $idrecurso = 0)
    {
        $task = new Motivo();
        $formTemplate = new MotivoForm();


        $response = $this->editClaseRecurso($request, $idrecurso, "AppBundle:Motivo", $task, $formTemplate, "servicio/motivo.html.twig"  );   
        if ( $request->isMethod( 'POST' ) ) {
            $content = $response->getContent();
             $jsonObj = json_decode($content);
            if ($jsonObj->success)
                return $this->redirectToRoute('motivogrid');
            else
                return $response;
        }
        else {
            return $response;
        }

    }
    
    
    /** Borra el objeto
     * @Route("/motivodelete", name="motivodelete")
     * @Route("/motivodelete/{idrecurso}", name="motivodeleteParams")
     */
    public function motivoDeleteAction(Request $request, $idrecurso = 0)
    {
       $task = new Motivo();
        return $this->deleteRecurso($request, $task, "AppBundle:Motivo", $idrecurso);
    }

    
    
    /**
     * @Route("/motivogrid", name="motivogrid")
     */
    public function motivoGridAction()
    {
        $source = new Entity('AppBundle:Motivo');
            
        return $this->showBasicGrid($source,"motivoedit", "motivodelete", "Motivo", "Motivos");
    }
    
    
       /**
    * Saves form data, including document saving
    * @param type $form
    * @return JsonResponse
    */
    public function saveData($form)
   {
    if ( $form->isValid( ) ) {
         $data = $form->getData();


         try
         {
          $em = $this->getDoctrine()->getManager(); 
          // Subo el fichero y luego lo guardo
          $data->upload();
          $em->persist($data);
          $em->flush();
          
          $response['success'] = true;
         } catch (\Exception $e) {

             $response['success'] = false;
             $response['cause'] = $e->getMessage();

         }

      }else{

          $response['success'] = false;
          $response['cause'] = $form->getErrorsAsString();

      }

      return new JsonResponse( $response );
       
   }
   
   
        /** 
      * Return cities filtered by province_id included in the request
     * @Route("/selectmotivos", name="select_motivos")
     */
    public function selectMotivosAction(Request $request)
    {
        $bomberos = $request->request->get('bomberos');

        $em = $this->getDoctrine()->getManager();
        $motivos = $em->getRepository('AppBundle:Motivo')
            ->findBy(array('bomberos' => $bomberos));
        
        
        $serializer = $this->container->get('serializer');                
        $motivosSerializer = $serializer->serialize($motivos, 'json');
        return new Response($motivosSerializer);
        //return new JsonResponse($cities);
    }

        /** 
      * Return cities filtered by province_id included in the request
     * @Route("/getidMotivoByCodigo", name="get_idMotivoByCodigo")
     */
    public function getidMotivoByCodigo(Request $request)
    {
        $codigo = $request->request->get('codigo_motivo');

        $em = $this->getDoctrine()->getManager();
        $motivo = $em->getRepository('AppBundle:Motivo')
            ->findOneBy(array('codigo' => $codigo));
        
        
        $id = ($motivo)? $motivo->getId() : 0;
        //$serializer = $this->container->get('serializer');                
        //$motivosSerializer = $serializer->serialize($id, 'json');
        return new Response($id);
        //return new JsonResponse($cities);
    }

        /** 
      * Return cities filtered by province_id included in the request
     * @Route("/getcodigomotivo", name="get_codigo_motivo")
     */
    public function getCodigoMotivoAction(Request $request)

    {
        $idMotivo = $request->request->get('id_motivo');

        $em = $this->getDoctrine()->getManager();
        $motivo = $em->getRepository('AppBundle:Motivo')
            ->find($idMotivo);
        
        
        /*$serializer = $this->container->get('serializer');                
        $motivosSerializer = $serializer->serialize($motivo->getCodigo(), 'json');*/
        return new Response(($motivo)? $motivo->getCodigo() : "");
        //return new JsonResponse($cities);
    }


          /** 
      * Return cities filtered by province_id included in the request
     * @Route("/geturlficha", name="get_url_ficha")
     */
    public function geturlfichaAction(Request $request)

    {
        $idMotivo = $request->request->get('id_motivo');

        $em = $this->getDoctrine()->getManager();
        $motivo = $em->getRepository('AppBundle:Motivo')
            ->find($idMotivo);
        
        
        /*$serializer = $this->container->get('serializer');                
        $motivosSerializer = $serializer->serialize($motivo->getCodigo(), 'json');*/
        return new Response(($motivo)? "../".$motivo->getWebRelativePath() : "");
        //return new JsonResponse($cities);
    }
  
  
    }
