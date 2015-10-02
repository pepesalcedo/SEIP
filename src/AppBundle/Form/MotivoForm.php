<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Diagnostico;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;


class MotivoForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
    $builder
        ->add('name', 'text', array('label' => 'Nombre:', 'max_length' => 50))
        ->add('codigo', 'text', array('label' => 'Código:', 'max_length' => 10))
        ->add('bomberos', 'choice', array('label' => 'Derivado a Bomberos?' , 'choices' => array('N' => 'No', 'S' => 'Si'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('file')            
        ->add('save', 'submit', array('label' => 'Crear Motivo'))

        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Motivo',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'motivo';
    }
}