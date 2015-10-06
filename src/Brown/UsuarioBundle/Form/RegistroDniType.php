<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 22/09/2015
 * Time: 09:50 AM
 * El formulario que el usuario debe completar para ver si existe su dni en la base de datos de vecinos, o si ya está registrado su dni.
 */

namespace Brown\UsuarioBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroDniType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dni')
        ->add('submit', 'submit', array(
            'label' => 'Continuar',
            'attr' => array(
                'class' => 'btn-success'
            )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brown\UsuarioBundle\Entity\Usuario'
        ));
    }


    public function getName()
    {
        return 'brown_usuarios_dni_registro';
    }

}