<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 01/10/2015
 * Time: 12:15 PM
 */

namespace Brown\UsuarioBundle\Services;

use Brown\UsuarioBundle\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;

class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * AuthenticationFailureHandler constructor.
     */
    public function __construct($httpKernelInterface, $em, $httpUtils)
    {
        $this->httpKernel = $httpKernelInterface;
        $this->em = $em;
        $this->httpUtils = $httpUtils;
        $this->setOptions(array());
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $repo = $this->em->getRepository('UsuarioBundle:Usuario');

        $usuario = $repo->findOneBy(array(
            'dni' => $request->get('_username')
        ));

        /* @var $usuario Usuario */

        if ($usuario)
        {

            $loginAttempts = $usuario->getLoginAttempts();
            if ($loginAttempts > 3)
            {
                $usuario->setActivo(false);
            }
            else
            {
                $usuario->setLoginAttempts($loginAttempts+1);
            }
            $this->em->persist($usuario);
            $this->em->flush();
            $exception = new AuthenticationException("hola");
            return parent::onAuthenticationFailure($request, $exception);
        }
    }


}