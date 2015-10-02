<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\AddTipoGrupoRecursoFieldSubscriber;
use AppBundle\Form\AddClaseRecursoFieldSubscriber;


class RecursoGridBaseForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToResource = 'claserecurso';
 
    
    $builder
         ->addEventSubscriber(new AddClaseRecursoFieldSubscriber($propertyPathToResource))
         ->addEventSubscriber(new AddTipoGrupoRecursoFieldSubscriber($propertyPathToResource))
         ->addEventSubscriber(new AddClaseTipoFieldSubscriber($propertyPathToResource))

        ->add('save', 'submit', array('label' => 'Buscar'))
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
        return 'recursoGrid';
    }
}