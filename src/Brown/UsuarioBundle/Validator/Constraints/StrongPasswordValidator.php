<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 02/10/2015
 * Time: 09:23 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StrongPasswordValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if( !preg_match("#[0-9]{" . StrongPassword::MIN_NUMBERS . "}+#", $value) ) {
            $this->context->addViolation(
                sprintf($constraint->messageNumeros, StrongPassword::MIN_NUMBERS, ((StrongPassword::MIN_NUMBERS == 1)?'':'s'))
            );
        }
        if( !preg_match("#[a-z]{" . StrongPassword::MIN_LETTERS . "}+#", $value) ) {
            $this->context->addViolation(
                sprintf($constraint->messageLetras, StrongPassword::MIN_LETTERS, ((StrongPassword::MIN_LETTERS == 1)?'':'s'))
            );
        }
        if (preg_match("/(.)\\1{" . (StrongPassword::MAX_CONSECUTIVE) . "}/", $value)) {
            $this->context->addViolation(
                sprintf($constraint->messageConsecutive, StrongPassword::MAX_CONSECUTIVE,
                    ((StrongPassword::MAX_CONSECUTIVE == 1)?'':'es'),
                    ((StrongPassword::MAX_CONSECUTIVE == 1)?'':'s')
                    )
            );
        }
    }

}