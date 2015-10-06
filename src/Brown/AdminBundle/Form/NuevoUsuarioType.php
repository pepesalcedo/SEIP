<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 09/09/2015
 * Time: 12:59 PM
 */

namespace Brown\AdminBundle\Form;


use Brown\UsuarioBundle\Entity\Usuario;
use Brown\UsuarioBundle\Validator\Constraints\DniNotExists;
use Brown\UsuarioBundle\Validator\Constraints\EmailExists;
use Brown\UsuarioBundle\Validator\Constraints\EmailNotExists;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;

class NuevoUsuarioType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellido', null, array(
                'attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('nombre', null, array(
                'attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('dni',null,array('attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('email', null, array(
                'attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'label' => 'Contraseña',
                    'attr' => array('placeholder' => 'Campo obligatorio')
                ),
                'second_options' => array(
                    'label' => 'Repetir contraseña',
                    'attr' => array('placeholder' => 'Campo obligatorio')
                ),
                'constraints' => array(
                    new Length(array(
                        'min' => Usuario::PASSWD_MIN_LENGTH,
                        'max' => Usuario::PASSWD_MAX_LENGTH,
                        'minMessage' => sprintf('La contraseña debe tener al menos %s caracteres.', Usuario::PASSWD_MIN_LENGTH),
                        'maxMessage' => sprintf('La contraseña debe tener %s o menos caracteres', Usuario::PASSWD_MAX_LENGTH)
                    ))
                )
            ))
            ->add('calle', null, array(
                'attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('calleNumero', null, array(
                'attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('piso', null, array(
                'required' =>false
            ))
            ->add('departamento', null, array(
                'required' => false
            ))
            ->add('entreCalles', null, array(
                'required' => false
            ))
            ->add('localidad')
            ->add('codigoPostal', null, array(
                'attr' => array('placeholder' => 'Campo obligatorio')
            ))
            ->add('telefono', null, array(
                'required' => false
            ))
            ->add('celular', null, array(
                'required' => false
            ))
            ->add('fechaDeNacimiento', 'date', array(
                'format' => 'd/M/y',
                'years' => range(1940, date('Y'))
            ))
            ->add('partida', null, array(
                'required' => false
            ))
            ->add('nacionalidad')
            ->add('activo', null, array(
                'required' => false,
                'label' => 'Usuario habilitado'
            ))
            ->add('permisos', null, array(
                'label' => 'Permisos',
                'attr' => array(
                    'class' => 'portal-multiselect'
                )
            ))
            ->add('serviciosBloqueados', null, array(
                'label' => 'Servicio Bloqueados',
                'attr' => array(
                    'class' => 'portal-multiselect'
                )
            ))
            ->add('submit', 'submit', array(
                'label' => 'Crear nuevo usuario',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brown\UsuarioBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'brown_admin_usuario_nuevo';
    }

}