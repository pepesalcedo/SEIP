<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 21/09/2015
 * Time: 10:05 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class DniNotExists extends Constraint
{

    public $message = 'El DNI "%string%" ya está registrado en el sistema.';

    public function validatedBy()
    {
        return 'brown_validator_dni_not_exists';
    }


}