<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 10/09/2015
 * Time: 10:25 AM
 */

namespace Brown\AdminBundle\Controller;


use Brown\AdminBundle\Form\EditarUsuarioType;
use Brown\SiteBundle\Util\Mensajes;
use Brown\UsuarioBundle\Entity\Clave;
use Brown\UsuarioBundle\Entity\Usuario;
use Brown\AdminBundle\Form\NuevoUsuarioType;
use Brown\UsuarioBundle\Validator\Constraints\DniNotExists;
use Brown\UsuarioBundle\Validator\Constraints\DniNotExistsValidator;
use JsonSchema\Constraints\NumberConstraint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Type;

class UsuarioController extends Controller
{

    const PARAM_PAGINA = 'p';
    const PARAM_BUSQUEDA = 'q';
    const PARAM_BLOQUEADO = 'b';

    const USUARIOS_POR_PAGINA = 20;

    public function listadoAction(Request $request)
    {


        $b = $request->get(self::PARAM_BLOQUEADO);

        //  Breadcrumbs
        $this->getAdminBC();

        $pagina = $request->get(self::PARAM_PAGINA, 1);
        if (!is_numeric($pagina)) $pagina = 1;

        $q = $request->get(self::PARAM_BUSQUEDA);

        $upp = self::USUARIOS_POR_PAGINA;

        $offset = ($pagina-1)*$upp;

        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');


        $usuarios = $repo->findListado($q, $upp, $offset, $b);

        //  Total
        $total = $repo->findTotalListado($q, $b);

        $paginas = ceil($total/$upp);

        return $this->render('@Admin/Usuario/listado.html.twig', array(
            'usuarios' => $usuarios,
            'total' => $total,
            'pagina' => $pagina,
            'paginas' => $paginas,
            'q' => $q,
            'b' => $b
        ));

    }


    public function nuevoDniAction(Request $request)
    {
        //  BC
        $this->getAdminBC()->addItem('Nuevo usuario');

        //  Formulario
        $form = $this->createFormBuilder()
            ->add('dni','text',array(
                'label' => 'DNI',
                'required' => true,
                'invalid_message' => 'Debe ingresar un número de DNI',
                'constraints' => array(
                    new DniNotExists(),
                    new Type(array('type' => 'numeric', 'message' => 'El DNI debe contener sólo números'))
                )
            ))
            ->add('submit','submit', array(
                'label' => 'Nuevo Usuario',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
        ->getForm();

        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                return $this->redirect($this->generateUrl('admin_usuarios_nuevo', array(
                    'dni' => $form->get('dni')->getData()
                )));
            }
        }

        return $this->render('@Admin/Usuario/nuevo-dni.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    /**
     * ADMIN
     * @param Request $request
     */
    public function nuevoAction(Request $request)
    {

        //  BC
        $this->getAdminBC()->addItem('Nuevo usuario');

        $em = $this->get('doctrine.orm.entity_manager');

        //  DNI
        $dni = $request->get('dni');

        //  Copiar
        $copiar = $request->get('copiar');

        if (!$dni || !is_numeric($dni))
        {
            $this->addFlash(Mensajes::TYPE_INFO, 'Debe ingresar un DNI válido');
            return $this->redirect($this->generateUrl('admin_usuarios_nuevo_dni'));
        }

        //  Buscar el usuario en la base de datos de vecino (padron)
        $vecino = $this->getVecino($dni);
        if ($vecino)
        {
            //  TODO: SI HAY UN VECINO, AUTOCOMPLETAR EL FORM:
            //  $usuario->setDni($dni);
        }

        $usuario = new Usuario();

        if ($copiar) {
            $usuarioACopiar = $em->getRepository('UsuarioBundle:Usuario')->find($copiar);
            if ($usuarioACopiar)
                $usuario = $usuarioACopiar;
        }
        $formType = new NuevoUsuarioType();
        $form = $this->createForm($formType, $usuario);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $this->encodeUserPassword($usuario);

                $em->persist($usuario);
                $em->flush();

                //  Guardar la clave
                $clave = new Clave();
                $clave->setUsuario($usuario);
                $clave->setClave($usuario->getPassword());
                $em->persist($clave);
                $em->flush();

                $this->addFlash(Mensajes::TYPE_SUCCESS, 'El usuario ha sido creado correctamente');
                return $this->redirect($this->generateUrl('admin_usuarios_listado'));
            }
        }
        return $this->render('@Admin/Usuario/nuevo.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    public function editarAction(Request $request)
    {

        $id = $request->get('id');
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->find($id);


        $this->getAdminBC()->addItem($usuario,
            $this->generateUrl('admin_usuarios_detalle', array('id' => $usuario->getId()))
            )->addItem('Editar');

        $formType = new EditarUsuarioType();
        $form = $this->createForm($formType, $usuario);
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                if (!$form->get('newPassword')->isEmpty())
                {
                    $newRawPassword = $form->get('newPassword')->getData();
                    $usuario->setPassword($newRawPassword);
                    $this->encodeUserPassword($usuario);
                    $this->addFlash(Mensajes::TYPE_INFO, 'La contraseña fue cambiada');
                    //  Guarda la nueva clave en el registro
                    $clave = new Clave();
                    $clave->setUsuario($usuario);
                    $clave->setClave($usuario->getPassword());
                    $em->persist($clave);
                }
                $em->persist($usuario);
                $em->flush();
                $this->addFlash(Mensajes::TYPE_SUCCESS, 'Los cambios han sido guardados correctamente');
                return $this->redirect($this->generateUrl('admin_usuarios_editar', array(
                    'id' => $usuario->getId()
                )));
            }
        }
        return $this->render('@Admin/Usuario/editar.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $form->createView()
        ));
    }

    public function blanquearPasswordAction(Request $request)
    {

        $id = $request->get('id');
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->find($id);

        $this->getAdminBC()->addItem($usuario, $this->generateUrl('admin_usuarios_detalle', array('id'=>$usuario->getId())))->addItem('Blanquear contraseña');

        $form = $this->createFormBuilder()
            ->add('ok', 'checkbox', array(
                'required' => true,
                'label' => 'Estoy seguro de resetear la contraseña de este usuario'
            ))
        ->add('submit','submit',array(
            'label' => 'Estoy seguro de regenerar la contraseña de este usuario',
            'attr' => array(
                'class' => 'btn-danger'
            )
        ))->getForm();
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $newRandomPassword = Usuario::generateRandomPassword();
                $usuario->setPassword($newRandomPassword);
                $this->encodeUserPassword($usuario);
                $em->persist($usuario);

                //  Guardar clave
                $clave = new Clave();
                $clave->setUsuario($usuario);
                $clave->setClave($usuario->getPassword());
                $em->persist($clave);

                $em->flush();

                //  Enviar email
                $asunto = 'Nueva contraseña';
                $body = $this->get('twig')->render('@Admin/emails/blanquear-password.html.twig', array(
                    'usuario' => $usuario,
                    'rawPassword' => $newRandomPassword
                ));
                $msg = \Swift_Message::newInstance($asunto, $body, 'text/html', 'utf8');
                $msg->addFrom('portal@brown.gob.ar', 'Portal Brown');
                $msg->addTo($usuario->getEmail(), $usuario->__toString());
                $mail = $this->get('mailer');
                $mail->send($msg);
                //  Fin Enviar email

                $this->addFlash(Mensajes::TYPE_SUCCESS, 'La nueva contraseña ha sido enviada a ' . $usuario->getEmail());
                return $this->redirect($this->generateUrl('admin_usuarios_detalle', array('id' => $usuario->getId())));
            }
        }
        return $this->render('@Admin/Usuario/blanquear-password.html.twig', array(
            'formulario' => $form->createView(),
            'usuario' => $usuario
        ));
    }

    public function borrarAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->find($id);

        $this->getAdminBC()->addItem($usuario,
            $this->generateUrl('admin_usuarios_detalle', array('id' => $usuario->getId()))
        )->addItem('Borrar usuario');

        $form = $this->createFormBuilder()
            ->add('ok', 'checkbox', array(
                'required' => true,
                'label' => 'Estoy seguro de querer borrar este usuario'
            ))
            ->add('submit','submit',array(
                'label' => 'Estoy seguro, deseo borrar el usuario',
                'attr' => array(
                    'class' => 'btn-danger'
                )
            ))->getForm();
        if ($request->isMethod(Request::METHOD_POST))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                $em->remove($usuario);
                $em->flush();
                $this->addFlash(Mensajes::TYPE_SUCCESS, 'El usuario ha sido eliminado correctamente');
                return $this->redirect($this->generateUrl('admin_usuarios_listado'));
            }
        }
        return $this->render('@Admin/Usuario/borrar.html.twig', array(
            'formulario' => $form->createView(),
            'usuario' => $usuario
        ));
    }

    public function detalleAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->find($id);

        $this->getAdminBC()->addItem($usuario);

        return $this->render('@Admin/Usuario/detalle.html.twig', array(
            'usuario' => $usuario
        ));
    }

    /**
     * @return \WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
     */
    private function getAdminBC()
    {
        $bc = $this->get('white_october_breadcrumbs');
        $bc->addItem('Administración', $this->get('router')->generate('admin_homepage'));
        $bc->addItem('Usuarios', $this->get('router')->generate('admin_usuarios_listado'));
        return $bc;
    }

    /**
     * @param Usuario $usuario
     */
    private function encodeUserPassword(Usuario &$usuario)
    {
        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
        $salt = $usuario->getSalt();
        $rawPassword = $usuario->getPassword();
        $encodedPassword = $encoder->encodePassword($rawPassword, $salt);
        $usuario->setPassword($encodedPassword);
    }

    private function getVecino($dni)
    {
        //  TODO: BUSCAR EN LA BASE DE DATOS DE VECINOS SI HAY ALGUNO CON EL DNI PARA AUTOCOMPLETAR EL FORMULARIO
        return null;
    }

    public function bloquearAction(Request $request) {

        $id = $request->get('id');
        $em = $this->get('doctrine.orm.default_entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->find($id);
        if (!$usuario)
        {
            $this->addFlash(Mensajes::TYPE_ERROR, 'El usuario que intenta bloquear no existe');
            return $this->redirect($this->generateUrl('admin_usuarios_listado'));
        }
        if ($usuario->isActivo()) {
            $usuario->setActivo(false);
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(Mensajes::TYPE_SUCCESS, "El usuario ha sido bloqueado correctamente.");
        } else{
            $this->addFlash(Mensajes::TYPE_INFO, "El usuario ya se encuentra bloqueado");
        }
        return $this->redirect($this->generateUrl('admin_usuarios_detalle', array('id'=>$usuario->getId())));
    }

}