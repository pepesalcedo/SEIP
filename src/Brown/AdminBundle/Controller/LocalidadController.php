<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 25/09/2015
 * Time: 02:38 PM
 */

namespace Brown\AdminBundle\Controller;


use Brown\AdminBundle\Form\Localidad\EditarLocalidadType;
use Brown\AdminBundle\Form\Localidad\NuevaLocalidadType;
use Brown\MunicipioBundle\Entity\Localidad;
use Brown\SiteBundle\Util\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocalidadController extends Controller
{

    public function listadoAction()
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $repo = $em->getRepository('MunicipioBundle:Localidad');
        $this->getAdminBC();
        $localidades = $repo->findBy(array(), array(
            'nombre' => 'ASC'
        ));
        return $this->render('@Admin/Localidad/listado.html.twig', array(
            'localidades' => $localidades
        ));
    }

    public function nuevoAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $formType = new NuevaLocalidadType();
        $localidad = new Localidad();
        $form = $this->createForm($formType, $localidad);
        $this->getAdminBC()->addItem('Nueva localidad');
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $em->persist($localidad);
                $em->flush();
                $this->addFlash(Mensajes::TYPE_SUCCESS, 'La localidad ha sido creada correctamente');
                return $this->redirect($this->generateUrl('admin_localidades_listado'));
            }
        }
        return $this->render('@Admin/Localidad/nuevo.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    public function editarAction(Request $request)
    {

        $id = $request->get('id');
        $em = $this->get('doctrine.orm.default_entity_manager');
        $repo = $em->getRepository('MunicipioBundle:Localidad');
        $localidad = $repo->find($id);
        if (!$localidad)
        {
            $this->addFlash(Mensajes::TYPE_ERROR, 'La localidad que intenta editar no existe');
            return $this->redirect($this->generateUrl('admin_localidades_listado'));
        }

        $this->getAdminBC()->addItem($localidad->getNombre())->addItem('Editar');
        $formType = new EditarLocalidadType();
        $form = $this->createForm($formType, $localidad);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $em->persist($localidad);
                $em->flush();
                $this->addFlash(Mensajes::TYPE_SUCCESS, "Los cambios han sido guardados correctamente");
                return $this->redirect($this->generateUrl('admin_localidades_editar', array(
                    "id" => $localidad->getId()
                )));
            }
        }
        return $this->render('@Admin/Localidad/editar.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    public function borrarAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->get('doctrine.orm.default_entity_manager');
        $repo = $em->getRepository('MunicipioBundle:Localidad');
        $localidad = $repo->find($id);
        if (!$localidad)
        {
            $this->addFlash(Mensajes::TYPE_ERROR, 'La localidad que intenta editar no existe');
            return $this->redirect($this->generateUrl('admin_localidades_listado'));
        }
        $userRepo = $em->getRepository('UsuarioBundle:Usuario');
        $usuariosEnEstaLocalidad = $userRepo->countByLocalidad($localidad);
        if ($usuariosEnEstaLocalidad > 0)
        {
            $this->addFlash(Mensajes::TYPE_WARNING, "No se puede borrar la localidad \"" . $localidad->getNombre() . "\" porque hay usuarios asignados a la misma.");
            return $this->redirect($this->generateUrl('admin_localidades_listado'));
        }
        $em->remove($localidad);
        $em->flush();
        $this->addFlash(Mensajes::TYPE_SUCCESS, "La localidad fue borrada correctamente");
        return $this->redirect($this->generateUrl('admin_localidades_listado'));
    }

    /**
     * @return \WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
     */
    private function getAdminBC()
    {
        $bc = $this->get('white_october_breadcrumbs');
        $bc->addItem('Administración', $this->get('router')->generate('admin_homepage'));
        $bc->addItem('Localidades', $this->get('router')->generate('admin_localidades_listado'));
        return $bc;
    }

}