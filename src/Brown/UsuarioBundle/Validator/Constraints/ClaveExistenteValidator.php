<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 01/10/2015
 * Time: 10:50 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;

use Brown\UsuarioBundle\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ClaveExistenteValidator extends ConstraintValidator
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TokenStorage
     */
    private $securityContext;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * ClaveExistenteValidator constructor.
     * @param EntityManager $em
     * @param TokenStorage $securityContext
     */
    public function __construct(EntityManager $em, TokenStorage $securityContext, EncoderFactory $encoderFactory)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->encoderFactory = $encoderFactory;
    }

    public function validate($value, Constraint $constraint)
    {
        $repo = $this->em->getRepository('UsuarioBundle:Clave');
        $user = $this->securityContext->getToken()->getUser();
        /* @var $user Usuario */
        $rawPassword = $value;
        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($rawPassword, $user->getSalt());
        $clave = $repo->findOneBy(array(
            'usuario' => $user->getId(),
            'clave' => $encodedPassword
        ));
        if ($clave)
        {
            $this->context->addViolation($constraint->message, array("%string%" => $value));
        }
    }

}