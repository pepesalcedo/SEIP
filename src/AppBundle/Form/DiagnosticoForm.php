<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Diagnostico;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;


class DiagnosticoForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
    $builder
        ->add('identificador', 'text', array('label' => 'Código:', 'max_length' => 5))
        ->add('descripcion', 'text', array('label' => 'Descripción:', 'max_length' => 60))
        ->add('estado', 'entity', array('label' => 'Estado:', 'class' => 'AppBundle:EstadoTabla','choice_label' => 'name'))
        ->add('save', 'submit', array('label' => 'Crear Centro'))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Diagnostico',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'diagnostico';
    }
}