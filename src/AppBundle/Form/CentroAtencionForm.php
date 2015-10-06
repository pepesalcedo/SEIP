<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\CentroAtencion;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;


class CentroAtencionForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToCity = 'localidad';
 
    
    $builder
        ->add('tipo', 'entity', array('label' => 'Tipo:', 'class' => 'AppBundle:TipoCentroAtencion','choice_label' => 'name'))
        ->add('nombre', 'text', array('label' => 'Nombre:', 'required' => true, 'max_length' => 50))
        ->add('descripcion', 'text', array('label' => 'Descripción:', 'max_length' => 40))
        ->add('calle', 'text', array('label' => 'Calle:','max_length' => 50))
        ->add('nro', 'text', array('label' => 'Nro:', 'required' => false, 'max_length' => 10))
        ->add('entrecalles', 'text', array('label' => 'Entrecalles:', 'required' => false, 'max_length' => 50))
        ->add('piso', 'text', array('label' => 'Piso:', 'required' => false, 'max_length' => 10))
        ->add('dto', 'text', array('label' => 'Dto:', 'required' => false, 'max_length' => 10))
        ->addEventSubscriber(new AddCityFieldSubscriber($propertyPathToCity))
        ->addEventSubscriber(new AddProvinceFieldSubscriber($propertyPathToCity))
        ->add('telefono', 'text', array('label' => 'Teléfono:', 'required' => false, 'max_length' => 50))
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
            'data_class'        => 'AppBundle\Entity\CentroAtencion',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'centroatencion';
    }
}