<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 02/10/2015
 * Time: 11:40 AM
 */

namespace Brown\UsuarioBundle\Services;


use Brown\SiteBundle\Util\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationListener
{

    const INTENTOS = 4;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * AuthenticationListener constructor.
     * @param ContainerInterface $container
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }


    /**
     * onAuthenticationFailure
     *
     * @author     Joe Sexton <joe@webtipblog.com>
     * @param     AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure( AuthenticationFailureEvent $event)
    {
        $user = $this->getUser();
        if ($user && $user->isActivo())
        {
            $loginAttempts = $user->getLoginAttempts();
            $loginAttempts++;
            if ($loginAttempts >= self::INTENTOS)
            {
                $user->setActivo(false);
                $this->container->get('session')->getFlashBag()->add(Mensajes::TYPE_WARNING,"Tu cuenta ha sido bloqueada. Por favor comunicate con la municipalidad para recuperar tu cuenta.");
            } else {
                $user->setLoginAttempts($loginAttempts);
                $intentosRestantes = self::INTENTOS - $loginAttempts;
                $mensaje = sprintf("Te queda%s %s intento%s antes de que se bloquee tu cuenta.", (($intentosRestantes == 1)?'':'n'), $intentosRestantes, (($intentosRestantes == 1)?'':'s'));
                $this->container->get('session')->getFlashBag()->add(Mensajes::TYPE_WARNING,$mensaje);
            }
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    /**
     * onAuthenticationSuccess
     *
     * @author     Joe Sexton <joe@webtipblog.com>
     * @param     InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess( InteractiveLoginEvent $event )
    {
        $user = $this->getUser();
        $user->setLoginAttempts(0);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param $dni
     * @return \Brown\UsuarioBundle\Entity\Usuario|null
     */
    private function getUser()
    {
        $dni = $this->container->get('request')->get('_username');
        $repo = $this->em->getRepository('UsuarioBundle:Usuario');
        $user = $repo->findOneBy(array(
            'dni' => $dni
        ));
        return $user;
    }

}