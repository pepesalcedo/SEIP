<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 11/09/2015
 * Time: 12:10 PM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailExistsValidator extends ConstraintValidator
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
        if (!$usuario)
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