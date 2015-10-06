<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 02/10/2015
 * Time: 09:21 AM
 */

namespace Brown\UsuarioBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class StrongPassword extends Constraint
{

    const MIN_NUMBERS = 2;
    const MIN_LETTERS = 2;
    const MAX_CONSECUTIVE = 2;

    public $messageNumeros = "La contraseña debe tener al menos %s número%s";
    public $messageLetras = "La contraseña debe tener al menos %s letra%s";
    public $messageConsecutive = "La contraseña no debe tener más de %s caracter%s consecutivo%s";

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

}