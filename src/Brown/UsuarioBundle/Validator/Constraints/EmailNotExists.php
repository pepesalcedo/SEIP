<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 21/09/2015
 * Time: 10:32 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class EmailNotExists extends Constraint
{
    public $message = 'El email "%string%" ya está registrado en el sistema.';

    public function validatedBy()
    {
        return 'brown_validator_email_not_exists';
    }

}