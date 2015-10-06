<?php

namespace Brown\UsuarioBundle\Controller;

use Brown\SiteBundle\Util\Mensajes;
use Brown\UsuarioBundle\Entity\Clave;
use Brown\UsuarioBundle\Entity\Usuario;
use Brown\UsuarioBundle\Form\PerfilDatosPersonalesType;
use Brown\UsuarioBundle\Form\PerfilType;
use Brown\UsuarioBundle\Form\RegistroDniType;
use Brown\UsuarioBundle\Form\RegistroType;
use Brown\UsuarioBundle\Validator\Constraints\EmailExists;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    const PARAM_CODIGO = 'codigo';

    public function indexAction()
    {
        return $this->render('UsuarioBundle:Default:index.html.twig');
    }

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@Usuario/Default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));

    }

    public function perfilAction()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $formType = new PerfilType();
        $em = $this->get('doctrine.orm.entity_manager');
        $usuario = $this->getUser();
        /* @var $usuario Usuario */
        $form = $this->createForm($formType, $usuario);

        $formDatosPersonalesType = new PerfilDatosPersonalesType();
        $formDatosPersonales = $this->createForm($formDatosPersonalesType, $usuario);


        if ($request->isMethod(Request::METHOD_POST)) {
            if ($request->request->has('brown_portal_usuario_perfil'))
            {
                $form->submit($request);
                if ($form->isValid()) {
                    $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                    $rawPassword = $form->get('newPassword')->getData();
                    $salt = $usuario->getSalt();
                    $encodedPassword = $encoder->encodePassword($rawPassword, $salt);
                    $usuario->setPassword($encodedPassword);
                    $clave = new Clave();
                    $clave->setUsuario($usuario);
                    $clave->setClave($encodedPassword);
                    $em->persist($clave);
                    $em->persist($usuario);
                    $em->flush();
                    $this->addFlash('success', 'La contraseña ha sido modificada correctamente');
                    return $this->redirect($this->generateUrl('usuarios_perfil', array('tab'=>'password')));
                }
            }

            if ($request->request->has('brown_perfil_datos_personales'))
            {
                $formDatosPersonales->submit($request);
                if ($formDatosPersonales->isValid())
                {
                    $em->persist($usuario);
                    $em->flush();
                    $this->addFlash(Mensajes::TYPE_SUCCESS, 'Los datos han sido guardados correctamente.');
                    return $this->redirect($this->generateUrl('usuarios_perfil'));
                }
            }
        }
        return $this->render('@Usuario/Default/perfil.html.twig', array(
            'formulario' => $form->createView(),
            'formulario_datos' => $formDatosPersonales->createView(),
            'tab' => $request->get('tab',null)
        ));
    }

    public function recuperarPasswordAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', 'email', array(
                'label' => 'E-Mail',
                'constraints' => array(
                    new EmailExists()
                )
            ))
            ->add('submit', 'submit', array(
                'label' => 'Recuperar mi contraseña',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
            ->getForm();

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->submit($request);
            if ($form->isValid()) {
                //  Busca el usuario
                $em = $this->get('doctrine.orm.entity_manager');
                $repo = $em->getRepository('UsuarioBundle:Usuario');
                $usuario = $repo->findOneBy(array(
                    'email' => $form->get('email')->getData()
                ));
                //  Genera el código de recuperación
                $code = $this->getEncodedId($usuario);
                //  Genera la url de recuperación
                $url = "http://" . $request->getHttpHost() . $this->generateUrl('usuarios_resetear_password', array('codigo' => $code));

                //  Envía el Email
                $asunto = "Recuperar contraseña";
                $body = $this->get('twig')->render('@Usuario/email/recuperar-password.html.twig', array(
                    'enlace' => $url,
                    'usuario' => $usuario
                ));
                $msg = \Swift_Message::newInstance($asunto, $body, 'text/html', 'utf-8');
                $msg->setFrom($this->container->getParameter('mailer_from'), $this->container->getParameter('mailer_from_name'));
                $msg->addTo($usuario->getEmail(), $usuario->__toString());
                $this->get('mailer')->send($msg);
                //  Fin Envía el Email

                $this->addFlash(Mensajes::TYPE_INFO, 'Le hemos enviado un correo con instrucciones para recuperar su contraseña.');
                return $this->redirect($this->generateUrl('site_homepage'));
            }
        }

        return $this->render('@Usuario/Default/recuperar-password.html.twig', array(
            'formulario' => $form->createView()
        ));
    }

    public function resetPasswordAction(Request $request)
    {
        $codigo = $request->get(self::PARAM_CODIGO);
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->findOneByCodigo($codigo, $this->getParameter('secret'));
        $newRawPassword = Usuario::generateRandomPassword();
        $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
        $newEncodedPassword = $encoder->encodePassword($newRawPassword, $usuario->getSalt());
        $usuario->setPassword($newEncodedPassword);
        $em->persist($usuario);
        $em->flush();

        //  Envía el Email
        $asunto = "Nueva contraseña";
        $body = $this->get('twig')->render('@Usuario/email/nueva-password.html.twig', array(
            'newPassword' => $newRawPassword
        ));
        $msg = \Swift_Message::newInstance($asunto, $body, 'text/html', 'utf-8');
        $msg->setFrom($this->container->getParameter('mailer_from'), $this->container->getParameter('mailer_from_name'));
        $msg->addTo($usuario->getEmail(), $usuario->__toString());
        $this->get('mailer')->send($msg);
        //  Fin Envía el Email

        $this->addFlash(Mensajes::TYPE_INFO, 'Le hemos enviado un correo con su nueva contraseña.');
        return $this->redirect($this->generateUrl('site_homepage'));

    }

    /**
     * @param Usuario $user
     * @return string
     */
    private function getEncodedId(Usuario $user)
    {
        $encodedId = $user->getId() . '+' . $this->getParameter("secret");
        $encodedId = md5($encodedId);
        //$encodedId = base64_encode($user->getId());
        return $encodedId;
    }

    /**
     * Formulario en el que el usuario sólo completa el dni
     * para revisar que no esté registrado aún o aparezca en
     * la base de datos de vecinos.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registroDniAction(Request $request)
    {
        $formType = new RegistroDniType();
        $form = $this->createForm($formType);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->submit($request);
            if ($form->isValid()) {
                return $this->redirect($this->generateUrl('usuarios_registro', array('dni' => $form->get('dni')->getData())));
            }
        }
        return $this->render('@Usuario/Default/registro-dni.html.twig', array(
            'formulario' => $form->createView()
        ));
    }


    public function registroAction(Request $request)
    {
        $dni = $request->get('dni');
        if (!$dni || !is_numeric($dni)) {
            $this->addFlash(Mensajes::TYPE_WARNING, 'Debes ingresar un DNI válido');
            return $this->redirect($this->generateUrl('usuarios_registro_dni'));
        }
        $formType = new RegistroType();
        $usuario = new Usuario();
        $usuario->setDni($dni);

        $buscadorExterno = $this->get('brown.usuario_externo');
        $usuarioExterno = $buscadorExterno->findUsuario($dni);
        if ($usuarioExterno) {
            //  TODO: Completar los datos del usuario con los del usuario externo
        }

        $form = $this->createForm($formType, $usuario);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->submit($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine.orm.default_entity_manager');

                $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                $rawPassword = $usuario->getPassword();
                $encodedPassword = $encoder->encodePassword($rawPassword, $usuario->getSalt());
                $usuario->setPassword($encodedPassword);



                $roleRepo = $em->getRepository('UsuarioBundle:Role');
                $role = $roleRepo->findOneBy(array(
                    'codigo' => 'ROLE_USER'
                ));
                $usuario->addRole($role);
                $usuario->setActivo(false);

                $em->persist($usuario);
                $em->flush();

                //  Guardar clave en el registro
                $clave = new Clave();
                $clave->setUsuario($usuario);
                $clave->setClave($encodedPassword);
                $em->persist($clave);
                $em->flush();

                //  Enviar e-mail de activación
                $encodedId = $this->getEncodedId($usuario);
                $twig = $this->get('twig');
                $url = "http://" . $request->getHttpHost() . $this->generateUrl('usuarios_confirmar_cuenta', array(self::PARAM_CODIGO => $encodedId));
                $html = $twig->render('@Usuario/email/confirmacion-email.html.twig', array('enlace' => $url));
                $asunto = 'Confirmación de cuenta en Portal Brown';
                $contentType = 'text/html';
                $charset = 'utf-8';
                $message = \Swift_Message::newInstance($asunto, $html, $contentType, $charset);
                $message->setTo($usuario->getEmail(), $usuario->__toString());
                $message->setFrom($this->container->getParameter('mailer_from'), $this->container->getParameter('mailer_from_name'));
                $mailer = $this->get('mailer');
                $mailer->send($message);

                $this->addFlash(Mensajes::TYPE_SUCCESS, "Su cuenta ha sido creada correctamente.");

                return $this->redirect($this->generateUrl('site_homepage'));
            }
        }

        return $this->render('@Usuario/Default/registro.html.twig', array(
            'formulario' => $form->createView()
        ));

    }

    public function confirmarCuentaAction(Request $request)
    {
        $codigo = $request->get(self::PARAM_CODIGO);
        $usuario = $this->getUsuarioByCodigo($codigo);
        if ($usuario && !$usuario->isActivo())
        {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $usuario->setActivo(true);
            $em->persist($usuario);
            $em->flush();
            //  Enviar email
            $asunto = "Cuenta confirmada";
            $html = $this->get('twig')->render('@Usuario/email/email-bienvenida.html.twig', array(
                'usuario' => $usuario
            ));
            $contentType = 'text/html';
            $charset = 'utf-8';
            $message = \Swift_Message::newInstance($asunto, $html, $contentType, $charset);
            $message->addTo($usuario->getEmail(), $usuario->__toString());
            $message->setFrom($this->container->getParameter('mailer_from'), $this->container->getParameter('mailer_from_name'));
            $mailer = $this->get('mailer');
            $mailer->send($message);
            //  Fin Enviar email
            $this->addFlash(Mensajes::TYPE_SUCCESS, 'Su cuenta ha sido confirmada correctamente. Ya puede ingresar.');
        }

        return $this->redirect($this->generateUrl('site_homepage'));
    }

    /**
     * @param $codigo
     * @return null|Usuario
     */
    private function getUsuarioByCodigo($codigo)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->findOneByCodigo($codigo, $this->getParameter('secret'));
        return $usuario;
    }

}
