<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 31/08/2015
 * Time: 11:20 AM
 */

namespace Brown\UsuarioBundle\Form;


use Brown\UsuarioBundle\Entity\Usuario;
use Brown\UsuarioBundle\Validator\Constraints\ClaveExistente;
use Brown\UsuarioBundle\Validator\Constraints\StrongPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;

class PerfilType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('passwordActual', 'password', array(
                'required' => true,
                'mapped' => false,
                'constraints' => array(
                    new UserPassword(array('message' => 'Su contraseña actual no coincide'))
                ),
                'label' => 'Contraseña actual'
            ))
            ->add('newPassword','repeated',array(
                'mapped' => false,
                'type' => 'password',
                'required' => true,
                'first_options' => array('label' => 'Nueva Contraseña'),
                'second_options' => array('label' => 'Repetir Nueva Contraseña'),
                'invalid_message' => 'Las contraseñas ingresadas no coinciden',
                'constraints' => array(
                    new Length(array(
                        'min' => Usuario::PASSWD_MIN_LENGTH,
                        'max' => Usuario::PASSWD_MAX_LENGTH,
                        'minMessage' => sprintf('La contraseña debe tener al menos %s caracteres.', Usuario::PASSWD_MIN_LENGTH),
                        'maxMessage' => sprintf('La contraseña debe tener %s o menos caracteres', Usuario::PASSWD_MAX_LENGTH)
                    )),
                    new ClaveExistente(),
                    new StrongPassword()
                )
            ))
            ->add('submit','submit', array(
                'label' => 'Guardar Cambios',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
            ->setAction('?tab=password')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brown\UsuarioBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'brown_portal_usuario_perfil';
    }

}