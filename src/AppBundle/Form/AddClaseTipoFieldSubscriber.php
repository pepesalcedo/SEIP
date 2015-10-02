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
use Doctrine\ORM\EntityRepository;

/**
 * Administrar el el tipo de recurso (PERSONA o VEHICULO), para filtrar las clases de recursos pertinentes
 * Funciona junto a AddTipoGrupoRecursoFieldSubscriber
 */
class AddClaseTipoFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToResource;
    private $disabled;
 
    public function __construct($propertyPathToResource, $disabled = false)
    {
        $this->propertyPathToResource = $propertyPathToResource;
        $this->disabled = $disabled;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addTipoGrupoRecursoForm($form, $clase = null)
    {
        $formOptions = array(
            'choices'  => array('P' => 'Personas', 'V' => 'Vehículos'),
            'expanded'      => true,
            'multiple'      => false,
            'mapped'        => false,
            'label'         => 'Clase:',
            //'disabled'     => $this->disabled,                
            'attr'          => array(
                   'class' => 'clasetiporecurso_selector',
                ),
        );
 
        if ($clase) {
            $formOptions['data'] = $clase;
        }
 
        $form->add('clasetipo', 'choice', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::getPropertyAccessor();
 
        $claseRecurso    = $accessor->getValue($data, $this->propertyPathToResource);
        $clase = ($claseRecurso) ? $claseRecurso->getClase() : 'P';
 
        $this->addTipoGrupoRecursoForm($form, $clase);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addTipoGrupoRecursoForm($form);
    }
}