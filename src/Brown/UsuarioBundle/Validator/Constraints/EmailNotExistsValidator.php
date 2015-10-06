<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 21/09/2015
 * Time: 10:35 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class EmailNotExistsValidator extends ConstraintValidator
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * EmailExistsValidator constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $repo = $this->getEm()->getRepository('UsuarioBundle:Usuario');
        $usuario = $repo->findOneBy(array(
            'email' => $value
        ));
        if ($usuario)
        {
            $this->context->addViolation($constraint->message, array("%string%" => $value));
        }
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

}