<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PlanillaServiciosForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
    $builder
            
        ->add('desde', 'datetime', array('label' =>'Desde', 'mapped' => false))
        ->add('hasta', 'datetime', array('label' =>'Hasta', 'mapped' => false))
        ->add('tipoServicio', 'choice', array('label' => '' , 'choices' => array('D' => 'Despacho', 'T' => 'Traslado'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('save', 'submit', array('label' => 'Generar informe'))    
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Servicio',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'servicio';
    }
}