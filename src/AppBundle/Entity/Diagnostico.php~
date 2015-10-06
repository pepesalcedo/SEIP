<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="diagnostico")
 * @UniqueEntity("identificador")
 */
class Diagnostico extends BasicEntity {
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
    * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=5, unique=true, nullable=false)
     *  @GRID\Column(title="Código", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 5,
     *      maxMessage = "Código no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $identificador;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     *  @GRID\Column(title="Descripción", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 100,
     *      maxMessage = "Descripción no puede ser más largo que {{ limit }} caracteres"
     * )     */
    protected $descripcion;
    
    /**
     * @ORM\ManyToOne(targetEntity="EstadoTabla")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
    *  @GRID\Column(field="estado.name", title="Estado", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $estado;

    
    public function __construct()
    {
    }


    public function __toString() {
        return ($this->descripcion != null)? $this->descripcion : "";
    }
    
    public function getDescripcionCompleta()
    {
        return $this->identificador . "-".$this->descripcion;
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
     * Set identificador
     *
     * @param string $identificador
     * @return Diagnostico
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;

        return $this;
    }

    /**
     * Get identificador
     *
     * @return string 
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Diagnostico
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * @param \AppBundle\Entity\EstadoTabla $estado
     * @return Diagnostico
     */
    public function setEstado(\AppBundle\Entity\EstadoTabla $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \AppBundle\Entity\EstadoTabla 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
