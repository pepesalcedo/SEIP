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
use AppBundle\Entity\Provincia;
use AppBundle\Entity\Localidad;

class AddProvinceFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCity;
    private $required;
 
    public function __construct($propertyPathToCity, $required = true)
    {
        $this->propertyPathToCity = $propertyPathToCity;
        $this->required = $required;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }
 
    private function addProvinceForm($form, $provincia = null)
    {
        $formOptions = array(
            'class'         => 'AppBundle:Provincia',
            'mapped'        => false,
            'label'         => 'Provincia:',
            'empty_value'   => 'Provincia',
            'required'      => $this->required,            
            'attr'          => array(
                'class' => 'province_selector',
            ),
        );
 
        if ($provincia) {
            $formOptions['data'] = $provincia;
        }
 
        $form->add('provincia', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor = PropertyAccess::getPropertyAccessor();
 
        $city    = $accessor->getValue($data, $this->propertyPathToCity);
        $provincia = ($city) ? $city->getProvincia() : null;
 
        $this->addProvinceForm($form, $provincia);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
 
        $this->addProvinceForm($form);
    }
}