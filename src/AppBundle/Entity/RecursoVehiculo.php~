<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Recurso
 *
 * @author Jose
 */
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="recursovehiculo") 
 * @UniqueEntity("patente")
 */
class RecursoVehiculo extends BasicEntity
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="ClaseRecurso", inversedBy="RecursoVehiculo")
     * @ORM\JoinColumn(name="claserecursovehiculo_id", referencedColumnName="id")
     *  @GRID\Column(field="claserecurso.descripcion", title="Clase Recurso", operatorsVisible=false, filter="select", selectFrom="source", visible = false)
     */
    protected $claserecurso;
    
    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * @GRID\Column(title="Patente", operatorsVisible=false)
    * @Assert\Length(
     *      max = 10,
     *      maxMessage = "patente no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $patente;

    /**
     * @ORM\Column(type="string", length=50)
     * @GRID\Column(title="Tipo Vehículo", operatorsVisible=false)
    * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Tipo Vehículo no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $tipovehiculo;
    
    
    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     * @GRID\Column(title="Régimen Laboral", operatorsVisible=false)
    * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Régimen laboral no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $regimen;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @GRID\Column(title="Fecha alta", operatorsVisible=false)
     */
    protected $fechaAlta;

    /**
     * @ORM\ManyToOne(targetEntity="EstadoTabla")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
    *  @GRID\Column(field="estado.name", title="Estado", operatorsVisible=false, filter="select", selectFrom="source")
    */
    protected $estado;
    
        /**
     * @ORM\ManyToMany(targetEntity="GrupoRecurso", mappedBy="vehiculos")
     */
    protected $gruposRecurso;

    

    public function __construct()
    {
        $this->setFechaAlta(new \DateTime());
    }
    
    public function __toString() {
        return ($this->patente != null)? $this->patente : "";
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
     * Set patente
     *
     * @param string $patente
     * @return RecursoVehiculo
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;

        return $this;
    }

    /**
     * Get patente
     *
     * @return string 
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * Set tipovehiculo
     *
     * @param string $tipovehiculo
     * @return RecursoVehiculo
     */
    public function setTipovehiculo($tipovehiculo)
    {
        $this->tipovehiculo = $tipovehiculo;

        return $this;
    }

    /**
     * Get tipovehiculo
     *
     * @return string 
     */
    public function getTipovehiculo()
    {
        return $this->tipovehiculo;
    }

    /**
     * Set regimen
     *
     * @param string $regimen
     * @return RecursoVehiculo
     */
    public function setRegimen($regimen)
    {
        $this->regimen = $regimen;

        return $this;
    }

    /**
     * Get regimen
     *
     * @return string 
     */
    public function getRegimen()
    {
        return $this->regimen;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return RecursoVehiculo
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set claserecurso
     *
     * @param \AppBundle\Entity\ClaseRecurso $claserecurso
     * @return RecursoVehiculo
     */
    public function setClaserecurso(\AppBundle\Entity\ClaseRecurso $claserecurso = null)
    {
        $this->claserecurso = $claserecurso;

        return $this;
    }

    /**
     * Get claserecurso
     *
     * @return \AppBundle\Entity\ClaseRecurso 
     */
    public function getClaserecurso()
    {
        return $this->claserecurso;
    }

    /**
     * Set estado
     *
     * @param \AppBundle\Entity\EstadoTabla $estado
     * @return RecursoVehiculo
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

    /**
     * Add gruposRecurso
     *
     * @param \AppBundle\Entity\GrupoRecurso $gruposRecurso
     * @return RecursoVehiculo
     */
    public function addGruposRecurso(\AppBundle\Entity\GrupoRecurso $gruposRecurso)
    {
        $this->gruposRecurso[] = $gruposRecurso;

        return $this;
    }

    /**
     * Remove gruposRecurso
     *
     * @param \AppBundle\Entity\GrupoRecurso $gruposRecurso
     */
    public function removeGruposRecurso(\AppBundle\Entity\GrupoRecurso $gruposRecurso)
    {
        $this->gruposRecurso->removeElement($gruposRecurso);
    }

    /**
     * Get gruposRecurso
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGruposRecurso()
    {
        return $this->gruposRecurso;
    }
}
