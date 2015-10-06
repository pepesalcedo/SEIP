<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 25/09/2015
 * Time: 10:19 AM
 */

namespace Brown\AdminBundle\Controller;


use Brown\ServicioBundle\Entity\Servicio;
use Brown\ServicioBundle\Form\EditarServicioType;
use Brown\ServicioBundle\Form\NuevoServicioType;
use Brown\SiteBundle\Util\Mensajes;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServicioController extends Controller
{

    /**
     * ADMIN
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listadoAction()
    {
        $this->addBC();
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('ServicioBundle:Servicio');
        $servicios = $repo->findAll();
        return $this->render('@Admin/Servicio/listado.html.twig', array(
            'servicios' => $servicios
        ));
    }

    /**
     * ADMIN
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function nuevoAction()
    {
        $this->addBC()->addItem('Nuevo servicio');
        $servicio = new Servicio();
        $formType = new NuevoServicioType();
        $form = $this->createForm($formType, $servicio);
        $request = $this->get('request');
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($servicio);
                $em->flush();
                $this->addFlash(Mensajes::TYPE_SUCCESS, 'Nuevo servicio creado');
                return $this->redirect($this->generateUrl('admin_servicios_listado'));
            }
        }
        return $this->render('@Admin/Servicio/nuevo.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    public function editarAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('ServicioBundle:Servicio');
        $servicio = $repo->find($id);
        $this->addBC()->addItem($servicio->getNombre())->addItem('Editar');
        $request = $this->get('request');
        $formType = new EditarServicioType();
        $form = $this->createForm($formType, $servicio);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $em->persist($servicio);
                $em->flush();
                $this->addFlash(Mensajes::TYPE_SUCCESS, 'Los cambios han sido guardados correctamente');
                return $this->redirect($this->generateUrl('admin_servicios_listado'));
            }
        }
        return $this->render('@Admin/Servicio/editar.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    /**
     * ADMIN
     * Borrar un servicio
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function borrarAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('ServicioBundle:Servicio');
        $servicio = $repo->find($id);
        $servicio->setUsuariosBloqueados(new ArrayCollection());
        $em->persist($servicio);
        $em->flush();
        $em->remove($servicio);
        $em->flush();
        $this->addFlash(Mensajes::TYPE_SUCCESS, 'El servicio ha sido borrado correctamente');
        return $this->redirect($this->generateUrl('admin_servicios_listado'));
    }

    /**
     * @return $this|\WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
     */
    private function addBC()
    {
        return $this->get('white_october_breadcrumbs')->addItem('Administración', $this->get('router')->generate('admin_homepage'))->addItem('Servicios', $this->generateUrl('admin_servicios_listado'));
    }

}