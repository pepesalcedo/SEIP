<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 21/09/2015
 * Time: 10:04 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DniNotExistsValidator extends ConstraintValidator
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * DniNotExistsValidator constructor.
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
            'dni' => $value
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