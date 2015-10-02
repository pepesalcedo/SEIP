<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Form;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
 

/**
 * Description of AddCityFieldSubscriber
 *
 * @author Jose
 */
class AddCentroFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCentro;
 
    public function __construct($propertyPathToCentro)
    {
        $this->propertyPathToCentro = $propertyPathToCentro;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addCentroForm($form, $tipo_id)
    {
        $formOptions = array(
            'class'         => 'AppBundle:CentroAtencion',
            'empty_value'   => 'Centro de atención',
            'label'         => 'Centro de Atención',
            'required'      => false,
            'attr'          => array(
                'class' => 'centro_selector',
                
            ),
            'query_builder' => function (EntityRepository $repository) use ($tipo_id) {
                $qb = $repository->createQueryBuilder('centro')
                    ->innerJoin('centro.tipo', 'tipo')
                    ->where('tipo.id = :tipo')
                    ->setParameter('tipo', $tipo_id)
                ;
 
                return $qb;
            }
        );
 
        $form->add($this->propertyPathToCentro, 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor    = PropertyAccess::createPropertyAccessor();
 
        $centro        = $accessor->getValue($data, $this->propertyPathToCentro);
        $tipo_id = ($centro) ? $centro->getTipo()->getId() : null;
 
        $this->addCentroForm($form, $tipo_id);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $tipo_id = array_key_exists('ubicacion', $data) ? $data['ubicacion'] : null;
 
        $this->addCentroForm($form, $tipo_id);
    }
}
