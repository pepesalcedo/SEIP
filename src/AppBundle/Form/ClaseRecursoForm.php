<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\CentroAtencion;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;


/**
 * centro atencion form
 */
class ClaseRecursoForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('clase', 'choice', array('label' => 'Clase:' , 'choices' => array('P' => 'Personas', 'V' => 'Vehículos'),
            'required' => true))
        ->add('tipo', 'entity', array('label' => 'Tipo:', 'class' => 'AppBundle:TipoGrupoRecurso','choice_label' => 'name'))
        ->add('descripcion', 'text', array('label' => 'Descripción:'))
        ->add('estado', 'entity', array('label' => 'Estado:', 'class' => 'AppBundle:EstadoTabla','choice_label' => 'name'))
        ->add('save', 'submit', array('label' => 'Guardar'))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\ClaseRecurso',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'claserecurso';
    }
}