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
 * Select la clase de recurso, en función de los combos seleccionados anteriormente en 
 * Funciona junto a AddTipoGrupoRecursoFieldSubscriber y AddClaseRecursoFieldSubscriber
 * 
 * @author Jose
 */
class AddClaseRecursoFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToResource;
 
    public function __construct($propertyPathToResource)
    {
        $this->propertyPathToResource = $propertyPathToResource;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA  => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
 
    private function addClaseRecursoForm($form, $tipo_id, $claseTipo)
    {
        $formOptions = array(
            'class'         => 'AppBundle:ClaseRecurso',
            'empty_value'   => 'Clase',
            'label'         => 'Clase Recurso',
            'attr'          => array(
                'class' => 'claseRecurso_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($tipo_id, $claseTipo) {
                $qb = $repository->createQueryBuilder('claserecurso')
                    ->innerJoin('claserecurso.tipo', 'tipocentroatencion')
                    ->where('tipocentroatencion.id = :tipo and claserecurso.clase = :clase')
                    ->setParameter('tipo', $tipo_id)
                    ->setParameter('clase', $claseTipo)
                ;
 
                return $qb;
            }
        );
 
        $form->add($this->propertyPathToResource, 'entity', $formOptions);
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $accessor    = PropertyAccess::createPropertyAccessor();
 
        $claseRecurso = $accessor->getValue($data, $this->propertyPathToResource);
        $tipo_id  = ($claseRecurso) ? $claseRecurso->getTipo()->getId() : null;
        $clase = ($claseRecurso) ? $claseRecurso->getClase() : 'P';
 
        $this->addClaseRecursoForm($form, $tipo_id, $clase);
    }
 
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        $tipo_id = array_key_exists('tipogruporecurso', $data) ? $data['tipogruporecurso'] : null;
        $clase = array_key_exists('clasetipo', $data) ? $data['clasetipo'] : 'P';
        
        $this->addClaseRecursoForm($form, $tipo_id, $clase);
    }
}
