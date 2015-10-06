<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 25/09/2015
 * Time: 02:26 PM
 */

namespace Brown\AdminBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServiciosBloqueadosType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviciosBloqueados')
            ->add('submit', 'submit', array(
                'label' => 'Guardar Cambios',
                'attr' => array(
                    'class' => 'btn-success'
                )
            ))
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
        return 'brown_usuarios_admin_servicios_bloqueados';
    }

}