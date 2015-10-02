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
class AddMotivoFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCity;
 
    public function __construct()
    {
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addMotivoForm($form, $bomberos)
    {
        $formOptions = array(
            'class'         => 'AppBundle:Motivo',
            'empty_value'   => 'Motivo',
            'label'         => 'Motivo:',
            'attr'          => array(
                'class' => 'motivo_selector',
            ),
            'required'      => false,
            'query_builder' => function (EntityRepository $repository) use ($bomberos) {
                $qb = $repository->createQueryBuilder('motivo')
                    ->where('motivo.bomberos = :bomberos')
                    ->setParameter('bomberos', $bomberos)
                ;
 
                return $qb;
            }
        );
 
        $form->add('motivo', 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        //$accessor    = PropertyAccess::createPropertyAccessor();
 
        $bomberos    = $data->getBomberos();
        //$bomberos = ($motivo) ? $motivo->getBomberos() : 'N';
 
        $this->addMotivoForm($form, $bomberos);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $bomberos = array_key_exists('bomberos', $data) ? $data['bomberos'] : null;
 
        $this->addMotivoForm($form, $bomberos);
    }
}
