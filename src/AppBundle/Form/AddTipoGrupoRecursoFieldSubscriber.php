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

/**
 * Administrar el combo de tipo de grupo de recurso, para filtrar las clases de recursos pertinentes
 * Funciona junto a AddClaseTipoFieldSubscriber
 */
class AddTipoGrupoRecursoFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToResource;
    private $disabled;
 
    public function __construct($propertyPathToResource, $disabled= false)
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
 
    private function addTipoGrupoRecursoForm($form, $tipoGrupoRecurso = null)
    {
        $formOptions = array(
            'class'         => 'AppBundle:TipoGrupoRecurso',
            'mapped'        => false,
            'label'         => 'Tipo Grupo Recurso',
            'empty_value'   => 'Tipo',
            //'disabled'     => $this->disabled,
            'attr'          => array(
                'class' => 'tipogruporecurso_selector',
            ),
        );
 
        if ($tipoGrupoRecurso) {
            $formOptions['data'] = $tipoGrupoRecurso;
        }
 
        $form->add('tipogruporecurso', 'entity', $formOptions);
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
        $tipo = ($claseRecurso) ? $claseRecurso->getTipo() : null;
 
        $this->addTipoGrupoRecursoForm($form, $tipo);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addTipoGrupoRecursoForm($form);
    }
}