<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;


class ServicioPacienteForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    $builder
        ->add('nombre', 'text', array('label' => 'Nombre:', 'max_length' => 50, 'required' => false))
        ->add('apellido', 'text', array('label' => 'Apellido:', 'max_length' => 50, 'required' => false))
        ->add('edad', 'integer', array('label' => 'Edad:', 'required' => false))
        ->add('tipoEdad', 'choice', array('label' => '' , 'choices' => array('D' => 'Dias','M' => 'Meses', 'A' => 'Años'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('sexo', 'choice', array('label' => 'Sexo:' , 'choices' => array('M' => 'Masculino', 'F' => 'Femenino'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('dni', 'integer', array('label' => 'DNI:', 'required' => false))
        ->add('obraSocial', 'text', array('label' => 'Obra social:', 'max_length' => 50, 'required' => false))
        ->add('diagnostico1', 'entity', array('label' => 'Diagnóstico Principal:', 'class' => 'AppBundle:Diagnostico','choice_label' => 'descripcionCompleta', 'required' => false))
        ->add('diagnostico2', 'entity', array('label' => 'Diagnóstico Secundario:', 'class' => 'AppBundle:Diagnostico','choice_label' => 'descripcionCompleta', 'required' => false))
        ->add('diagnostico3', 'entity', array('label' => 'Diagnóstico 3:', 'class' => 'AppBundle:Diagnostico','choice_label' => 'descripcionCompleta', 'required' => false))
        ->add('diagnostico4', 'entity', array('label' => 'Diagnóstico 4:', 'class' => 'AppBundle:Diagnostico','choice_label' => 'descripcionCompleta', 'required' => false))
        ->add('diagnostico5', 'entity', array('label' => 'Diagnóstico 5:', 'class' => 'AppBundle:Diagnostico','choice_label' => 'descripcionCompleta', 'required' => false))
        ->add('FR', 'text', array('label' => 'FR:', 'max_length' => 10, 'required' => false))
        ->add('FC', 'text', array('label' => 'FC:', 'max_length' => 10, 'required' => false))
        ->add('TA', 'text', array('label' => 'TA:', 'max_length' => 10, 'required' => false))
        ->add('pulso', 'text', array('label' => 'Pulso:', 'max_length' => 10, 'required' => false))
        ->add('temperatura', 'text', array('label' => 'Temperatura:', 'max_length' => 10, 'required' => false))
        ->add('satO2', 'text', array('label' => 'Sat O2:', 'max_length' => 10, 'required' => false))
        ->add('hgt', 'text', array('label' => 'HGT:', 'max_length' => 10, 'required' => false))
        ->add('glasgow', 'text', array('label' => 'Glasgow:', 'max_length' => 10, 'required' => false))
        ->add('embarazo', 'choice', array('label' => 'Embarazo:' , 'choices' => array('S' => 'Si', 'N' => 'No'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('controlado', 'choice', array('label' => 'Controlado:' , 'choices' => array('S' => 'Si', 'N' => 'No'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('deriesgo', 'choice', array('label' => 'De Riesgo:' , 'choices' => array('S' => 'Si', 'N' => 'No'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('semanasgestacion', 'integer', array('label' => 'Semanas de gestación:', 'required' => false))
        ->add('trabajoparto', 'choice', array('label' => 'Trabajo de Parto:' , 'choices' => array('S' => 'Si', 'N' => 'No'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))

        ->add('save', 'submit', array('label' => 'Guardar Paciente'))    
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
        return 'serviciopaciente';
    }
}