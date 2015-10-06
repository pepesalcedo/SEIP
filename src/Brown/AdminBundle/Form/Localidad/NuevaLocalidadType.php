<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 25/09/2015
 * Time: 02:47 PM
 */

namespace Brown\AdminBundle\Form\Localidad;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NuevaLocalidadType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('submit', 'submit', array(
                'label' => 'Crear Localidad',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brown\MunicipioBundle\Entity\Localidad'
        ));
    }

    public function getName()
    {
        return 'brown_admin_localidad_nueva';
    }

}