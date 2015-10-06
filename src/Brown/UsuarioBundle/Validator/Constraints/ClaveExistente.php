<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 01/10/2015
 * Time: 10:49 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ClaveExistente extends Constraint
{

    public $message = 'La clave ingresada ya fue establecida anteriormente. Por favor ingrese otra.';

    public function validatedBy()
    {
        return "brown_validator_clave_nueva";
    }

}