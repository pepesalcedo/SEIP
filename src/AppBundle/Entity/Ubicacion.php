<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IngresoLlamado
 *
 * @author Jose
 */
namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="ubicacion")
 */
class Ubicacion
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=1)
     */
    protected $tipoServicio;


    public function __construct()
    {
    }
    
    public function __toString() {
        return ($this->name != null)? $this->name : "";
    }




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Ubicacion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tipoServicio
     *
     * @param string $tipoServicio
     * @return Ubicacion
     */
    public function setTipoServicio($tipoServicio)
    {
        $this->tipoServicio = $tipoServicio;

        return $this;
    }

    /**
     * Get tipoServicio
     *
     * @return string 
     */
    public function getTipoServicio()
    {
        return $this->tipoServicio;
    }
}
