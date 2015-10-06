<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 07/09/2015
 * Time: 11:33 AM
 */

namespace Brown\ServicioBundle\Form;


use Brown\ServicioBundle\Entity\Servicio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NuevoServicioType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $estados = Servicio::getEstados();
        $estadosOpciones = array();
        foreach ($estados as $estado)
        {
            $estadosOpciones[$estado] = Servicio::getEstadoLabel($estado);
        }
        $builder
            ->add('nombre')
            ->add('estado', 'choice', array(
                'choices' => $estadosOpciones
            ))
            ->add('descripcion')
            ->add('url')
            ->add('submit', 'submit', array(
                'label' => 'Crear nuevo servicio',
                'attr' => array('class' => 'btn-success')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brown\ServicioBundle\Entity\Servicio'
        ));
    }

    public function getName()
    {
        return 'brown_portal_nuevo_servicio';
    }

}