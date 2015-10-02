<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\AddTipoGrupoRecursoFieldSubscriber;
use AppBundle\Form\AddClaseRecursoFieldSubscriber;


class RecursoVehiculoForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToResource = 'claserecurso';
 
    
    $builder
         ->addEventSubscriber(new AddClaseRecursoFieldSubscriber($propertyPathToResource))
         ->addEventSubscriber(new AddTipoGrupoRecursoFieldSubscriber($propertyPathToResource, true))
         ->addEventSubscriber(new AddClaseTipoFieldSubscriber($propertyPathToResource, true))

        ->add('patente', 'text', array('label' => 'Patente:', 'max_length' => 10))
        ->add('tipovehiculo', 'text', array('label' => 'Tipo de vehículo:', 'max_length' => 50))
        ->add('regimen', 'choice', array('label' => 'Régimen:' , 'choices' => array('Contratado' => 'Contratado', 'Municipal' => 'Propio o Municipal', 'Otros' => 'Otros'),
            'required' => false))

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
            'data_class'        => 'AppBundle\Entity\RecursoVehiculo',
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