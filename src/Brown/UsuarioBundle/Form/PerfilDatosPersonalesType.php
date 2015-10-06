<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 01/10/2015
 * Time: 11:49 AM
 */

namespace Brown\UsuarioBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PerfilDatosPersonalesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni', null, array(
                'label' => 'DNI'
            ))
            ->add('nombre', null, array(
                'label' => 'Nombre'
            ))
            ->add('apellido', null,  array(
                'label' => 'Apellido'
            ))
            ->add('email', 'email', array(
                'label' => 'Correo Electrónico'
            ))
            ->add('calle', null, array(
                'label' => 'Calle'
            ))
            ->add('calleNumero', null, array(
                'label' => 'Nro'
            ))
            ->add('entreCalles', null, array(
                'label' => 'Entre calles / esquina'
            ))
            ->add('piso', null, array(
                'label' => 'Piso'
            ))
            ->add('departamento', null, array(
                'label' => 'Dto'
            ))
            ->add('localidad', null, array(
                'label' => 'Localidad'
            ))
            ->add('telefono', null, array(
                'label' => 'Teléfono'
            ))
            ->add('partida', null, array(
                'label' => 'Partida'
            ))
            ->add('codigoPostal', null, array(
                'label' => 'Cod Postal'
            ))
            ->add('nacionalidad', null, array(
                'label' => 'Nacionalidad'
            ))
            ->add('fechaDeNacimiento', 'date', array(
                'format' => 'd/M/y',
                'years' => range(1940, date('Y'))
            ))
            ->add('submit','submit', array(
                'label' => 'Guardar Cambios',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
            ->setAction('?')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brown\UsuarioBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'brown_perfil_datos_personales';
    }

}