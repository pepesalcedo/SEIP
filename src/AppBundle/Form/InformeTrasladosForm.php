<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class InformeTrasladosForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
    $builder
            
        ->add('desde', 'datetime', array('label' =>'Desde', 'mapped' => false))
        ->add('hasta', 'datetime', array('label' =>'Hasta', 'mapped' => false))
        ->add('diagnostico1', 'entity', array('label' => 'Diagnóstico Principal:', 'class' => 'AppBundle:Diagnostico','choice_label' => 'descripcion', 'required' => false, 'empty_value'   => 'Todos'))
        ->add('tipoInforme', 'choice', array('label' => 'Tipo informe:' , 'choices' => array('D' => 'Detalle', 'E' => 'Estadisticas'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            'mapped' => false))
        ->add('save', 'submit', array('label' => 'Generar informe'))    
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\ServicioPaciente',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'servicioPaciente';
    }
}