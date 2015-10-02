<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * centro atencion form
 */
class UbicacionForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder
            ->setAction($this->_action)
            ->add('name', 'text', array('label' => 'Población:'))
             ->add('tipoServicio', 'choice', array('label' => 'Tipo Servicio' , 'choices' => array('D' => 'Despacho', 'T' => 'Traslado'),         
                'multiple' => false,
                'expanded' => true,
                'required' => true))
            ->add('save', 'submit', array('label' => 'Guardar Localidad'))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Ubicacion',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ubicacion';
    }
}