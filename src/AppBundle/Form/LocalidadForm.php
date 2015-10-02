<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * centro atencion form
 */
class LocalidadForm extends AbstractType
{
    // accion que debe tener el form
    private $_action;
    
    // action para pasar al formulario
    public function getAction()
    {
        return $this->_action;
    }
    
    public function setAction($action)
    {
        $this->_action = $action;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder
            ->setAction($this->_action)
            ->add('provincia', 'entity', array('label' => 'Provincia:', 'class' => 'AppBundle:Provincia','choice_label' => 'name'))
            ->add('name', 'text', array('label' => 'Población:'))
            ->add('partido', 'text', array('label' => 'Partido:'))
            ->add('save', 'submit', array('label' => 'Guardar Localidad'))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Localidad',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'localidad';
    }
}