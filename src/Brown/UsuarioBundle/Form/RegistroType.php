<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 22/09/2015
 * Time: 09:45 AM
 */

namespace Brown\UsuarioBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellido')
            ->add('nombre')
            ->add('email')
            ->add('dni')
            ->add('fechaDeNacimiento', 'date', array(
                'format' => 'd/M/y',
                'years' => range(1940, date('Y'))
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'label' => 'Contraseña'
                ),
                'second_options' => array(
                    'label' => 'Repetir contraseña'
                )
            ))
            ->add('calle')
            ->add('calleNumero')
            ->add('piso')
            ->add('departamento')
            ->add('entreCalles')
            ->add('localidad')
            ->add('codigoPostal')
            ->add('telefono')
            ->add('celular')
            ->add('submit', 'submit', array(
                'label' => 'Registrarme',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
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
        return 'brown_usuarios_registro';
    }

}