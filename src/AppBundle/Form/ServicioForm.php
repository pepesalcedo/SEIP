<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\AddCityFieldSubscriber;
use AppBundle\Form\AddProvinceFieldSubscriber;
use AppBundle\Form\AddTipoCentroFieldSubscriber;
use AppBundle\Form\AddCentroFieldSubscriber;
use AppBundle\Form\AddMotivoFieldSubscriber;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class ServicioForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $propertyPathToCity = 'localidad';
        $propertyPathToCentro = 'centroAtencion';
 
    
    $builder
            
        ->add('numero', 'text', array('label' => 'Número:', 'max_length' => 40))
        ->add('fecha', 'datetime', array('label' => 'Fecha:','widget' => 'single_text','format' => 'dd-MM-yyyy'))
        ->add('tipoServicio', 'choice', array('label' => '' , 'choices' => array('D' => 'Despacho', 'T' => 'Traslado'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('estado', 'entity', array('label' => 'Estado:', 'class' => 'AppBundle:EstadoTabla','choice_label' => 'name', 'required' => false))
        ->add('telefono', 'text', array('label' => 'Teléfono:', 'required' => false, 'max_length' => 50))
        ->add('bomberos', 'choice', array('label' => 'Derivado a Bomberos?' , 'choices' => array('N' => 'No', 'S' => 'Si'),         
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        ->add('cobertura', 'choice', array('label' => 'En Cobertura?' , 'choices' => array('N' => 'No', 'S' => 'Si'),
            'multiple' => false,
            'expanded' => true,
            'required' => true))
        //->add('motivo', 'entity', array('label' => 'Motivo:', 'class' => 'AppBundle:Motivo','choice_label' => 'name', 'required' => false),
        ->addEventSubscriber(new AddMotivoFieldSubscriber())
       /* ->add('motivo', 'genemu_jqueryautocomplete_entity', array(
            'class' => 'AppBundle\Entity\Motivo',
            'property' => 'name', 'required' => false,
            'data_class' => 'AppBundle\Entity\Motivo'

        ))*/
            
        ->add('ingresollamado', 'entity', array('label' => 'Ingreso del llamado:' ,'class' => 'AppBundle:IngresoLlamado','choice_label' => 'name', 'required' => false))
        // faltan código y tiopo dependiendo del ingresoLlamado
        ->add('calle', 'text', array('label' => 'Calle:', 'required' => true, 'max_length' => 50))
        ->add('nro', 'text', array('label' => 'Nro:', 'required' => false, 'max_length' => 10))
        ->add('entrecalles', 'text', array('label' => 'Entrecalles/ Esquina:', 'required' => false, 'max_length' => 50))
        ->add('piso', 'text', array('label' => 'Piso:', 'required' => false, 'max_length' => 10))
        ->add('dto', 'text', array('label' => 'Dto:', 'required' => false, 'max_length' => 10))
        ->add('monoblock', 'text', array('label' => 'Monoblock:', 'required' => false, 'max_length' => 10))
        ->add('barrio', 'text', array('label' => 'Barrio:', 'required' => false, 'max_length' => 10))
        ->addEventSubscriber(new AddCityFieldSubscriber($propertyPathToCity, false))
        ->addEventSubscriber(new AddProvinceFieldSubscriber($propertyPathToCity, false))
        ->add('referencia', 'text', array('label' => 'Referencia:', 'required' => false, 'max_length' => 100))
        ->add('movillogico', 'entity', array('label' => 'Móvil lógico:',
            'class' => 'AppBundle:GrupoRecurso','choice_label' => 'descripcion', 'required' => false,
            'query_builder' => function(EntityRepository $repository) {
                  $qb = $repository->createQueryBuilder('s');
                  $qb->innerJoin('s.tipo', 't');                  
                  return $qb->where($qb->expr()->eq('t.name', "'SIEP'")); // filtramos por todos los que son SEIP, !!!TODO, configurarlo
              },))

        ->add('horaLlamado', 'time', array('label' => 'Hora llamado:','widget' => 'choice', 'required' => false))
        ->add('horaDespacho', 'time', array('label' => 'Hora Despacho:','widget' => 'choice', 'required' => false))
        ->add('horaSalidaBase', 'time', array('label' => 'Hora Salida Base:','widget' => 'choice', 'required' => false))
        ->add('horaLlegadaDestino', 'time', array('label' => 'Hora Llegada Destino:','widget' => 'choice', 'required' => false))
        ->add('horaSalidaDestino', 'time', array('label' => 'Hora Salida Destino:','widget' => 'choice', 'required' => false))
        ->add('horaLlegadaHospital', 'time', array('label' => 'Hora Llegada Hospital:','widget' => 'choice', 'required' => false))
        ->add('horaSalidaHospital', 'time', array('label' => 'Hora Salida Hospital:','widget' => 'choice', 'required' => false))
        ->add('horaLlegadaBase', 'time', array('label' => 'Hora Llegada a base:','widget' => 'choice', 'required' => false))
        ->add('horaDisponible', 'time', array('label' => 'Hora Disponible:','widget' => 'choice', 'required' => false))

        ->addEventSubscriber(new AddTipoCentroFieldSubscriber($propertyPathToCentro))
        ->addEventSubscriber(new AddCentroFieldSubscriber($propertyPathToCentro))

        //->add('centroAtencion', 'entity', array('class' => 'AppBundle:CentroAtencion','choice_label' => 'descripcion', 'required' => false))
        //->add('ubicacion', 'entity', array('class' => 'AppBundle:Ubicacion','choice_label' => 'name', 'required' => false))
        ->add('demoraDespacho', 'entity', array('label' => 'Demora Despacho:', 'class' => 'AppBundle:DemoraDespacho','choice_label' => 'name', 'required' => false))
        ->add('observaciones', 'textarea', array('label' => 'Observaciones:', 'required' => false, 'max_length' => 100))
        ->add('tribunal', 'text', array('label' => 'Tribunal:', 'required' => false, 'max_length' => 50))
        ->add('caratula', 'text', array('label' => 'Carátula:', 'required' => false, 'max_length' => 50))
        ->add('causa', 'text', array('label' => 'Causa/Expediente:', 'required' => false, 'max_length' => 50))

        ->add('centroAtencionTraslado','entity', array('label' => 'Trasladado a:', 'class' => 'AppBundle:CentroAtencion','choice_label' => 'descripcion', 'required' => false))    
        ->add('sector', 'text', array('label' => '', 'required' => false, 'max_length' => 50))
        ->add('destinoFinal','entity', array('label' => 'Destino Final:', 'class' => 'AppBundle:DestinoFinal','choice_label' => 'name', 'required' => false))    
        ->add('motivoInicial', 'text', array('label' => 'Motivo Inicial:', 'required' => false, 'max_length' => 50))
        ->add('medicoSolicita', 'text', array('label' => 'Médico solicitante:', 'required' => false, 'max_length' => 100))
        ->add('medicoRecibe', 'text', array('label' => 'Médico que Recibe:', 'required' => false, 'max_length' => 100))
        ->add('save', 'submit', array('label' => 'Crear Servicio'))    
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Servicio',
             //'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'servicio';
    }
}