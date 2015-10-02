<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\AddTipoGrupoRecursoFieldSubscriber;
use AppBundle\Form\AddClaseRecursoFieldSubscriber;


class RecursoPersonaForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToResource = 'claserecurso';
 
    
    $builder
         ->addEventSubscriber(new AddTipoGrupoRecursoFieldSubscriber($propertyPathToResource, true))
         ->addEventSubscriber(new AddClaseTipoFieldSubscriber($propertyPathToResource, true))
         ->addEventSubscriber(new AddClaseRecursoFieldSubscriber($propertyPathToResource))

        ->add('dni', 'text', array('label' => 'DNI:', 'max_length' => 10))
        ->add('nombre', 'text', array('label' => 'Nombre:', 'max_length' => 50))
        ->add('apellido', 'text', array('label' => 'Apellido:', 'max_length' => 50))
        ->add('profesion', 'text', array('label' => 'Profesión:', 'max_length' => 50))
        ->add('regimen', 'choice', array('label' => 'Régimen Laboral:' , 'choices' => array('Contratado' => 'Contratado', 'Municipal' => 'Municipal', 'Otros' => 'Otros'),
            'required' => false))
        ->add('estado', 'entity', array('label' => 'Estado:', 'class' => 'AppBundle:EstadoTabla','choice_label' => 'name'))
        ->add('save', 'submit', array('label' => 'Crear Persona'))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\RecursoPersona',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'recurso';
    }
}