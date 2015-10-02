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
use AppBundle\Entity\Provincia;
use AppBundle\Entity\Localidad;
 

/**
 * Description of AddCityFieldSubscriber
 *
 * @author Jose
 */
class AddCityFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCity;
    private $required;
 
    public function __construct($propertyPathToCity, $required = false)
    {
        $this->propertyPathToCity = $propertyPathToCity;
        $this->required = $required;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addCityForm($form, $province_id)
    {
        $formOptions = array(
            'class'         => 'AppBundle:Localidad',
            'empty_value'   => 'Ciudad',
            'label'         => 'Ciudad:',
            'required'      => $this->required,
            'attr'          => array(
                'class' => 'city_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($province_id) {
                $qb = $repository->createQueryBuilder('ciudad')
                    ->innerJoin('ciudad.provincia', 'provincia')
                    ->where('provincia.id = :provincia')
                    ->setParameter('provincia', $province_id)
                ;
 
                return $qb;
            }
        );
 
        $form->add($this->propertyPathToCity, 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor    = PropertyAccess::createPropertyAccessor();
 
        $city        = $accessor->getValue($data, $this->propertyPathToCity);
        $province_id = ($city) ? $city->getProvincia()->getId() : null;
 
        $this->addCityForm($form, $province_id);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $province_id = array_key_exists('provincia', $data) ? $data['provincia'] : null;
 
        $this->addCityForm($form, $province_id);
    }
}
