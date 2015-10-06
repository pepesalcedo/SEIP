<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 10/09/2015
 * Time: 10:54 AM
 */

namespace Brown\AdminBundle\Form;


use Brown\UsuarioBundle\Entity\Usuario;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class EditarUsuarioType extends NuevoUsuarioType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('submit')
            ->remove('password')
            ->add('newPassword','repeated',array(
                'type' => 'password',
                'first_options' => array(
                    'label' => 'Nueva contraseña'
                ),
                'second_options' => array(
                    'label' => 'Repetir nueva contraseña'
                ),
                'required' => false,
                'mapped' => false,
                'constraints' => array(
                    new Length(array(
                        'min' => Usuario::PASSWD_MIN_LENGTH,
                        'max' => Usuario::PASSWD_MAX_LENGTH,
                        'minMessage' => sprintf('La contraseña debe tener al menos %s caracteres.', Usuario::PASSWD_MIN_LENGTH),
                        'maxMessage' => sprintf('La contraseña debe tener %s o menos caracteres', Usuario::PASSWD_MAX_LENGTH)
                    ))
                )
            ))
            ->add('submit','submit', array(
                'label' => 'Guardar cambios',
                'attr' => array(
                    'class' => 'btn-primary'
                )
            ))
        ;
    }

}