<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form;
 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AddTipoCentroFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCentro;
 
    public function __construct($propertyPathToCentro)
    {
        $this->propertyPathToCentro = $propertyPathToCentro;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addTipoCentroForm($form, $tipo = null)
    {
        $formOptions = array(
            'class'         => 'AppBundle:TipoCentroAtencion',
            'mapped'        => false,
            'label'         => 'Ubicación:',
            'empty_value'   => 'Tipo centro',
            'required'      => false,
            'attr'          => array(
                'class' => 'tipocentro_selector',
            ),
        );
 
        if ($tipo) {
            $formOptions['data'] = $tipo;
        }
 
        $form->add('ubicacion', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::getPropertyAccessor();
 
        $centro    = $accessor->getValue($data, $this->propertyPathToCentro);
        $tipo = ($centro) ? $centro->getTipo() : null;
 
        $this->addTipoCentroForm($form, $tipo);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addTipoCentroForm($form);
    }
}