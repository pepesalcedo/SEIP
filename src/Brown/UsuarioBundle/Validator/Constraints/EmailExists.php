<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 11/09/2015
 * Time: 12:09 PM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class EmailExists extends Constraint
{

    public $message = 'El email "%string%" no está registrado en el sistema.';

    public function validatedBy()
    {
        return 'brown_validator_email_exists';
    }

}