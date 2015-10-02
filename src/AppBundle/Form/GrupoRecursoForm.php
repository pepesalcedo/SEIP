<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\AddTipoGrupoRecursoFieldSubscriber;
use AppBundle\Form\AddClaseRecursoFieldSubscriber;


class GrupoRecursoForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
    $builder
        ->add('tipo', 'entity', array('label' => 'Tipo:','class' => 'AppBundle:TipoGrupoRecurso','choice_label' => 'name'))
        ->add('descripcion', 'text', array('label' => 'Descripción:'))
        ->add('fechaInicio', 'datetime', array('label' => 'Fecha Inicio de trabajo:'))
        ->add('fechaFin', 'datetime', array('label' => 'Fecha Fin de trabajo:','required' =>false))
        ->add('estado', 'entity', array('label' => 'Estado:','class' => 'AppBundle:EstadoTabla','choice_label' => 'name'))            
        ->add('save', 'submit', array('label' => 'Crear Grupo Recurso'))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\GrupoRecurso',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gruporecurso';
    }
}