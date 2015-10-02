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
 * @ORM\Table(name="recursopersona") 
 * @UniqueEntity("dni")
 */
class RecursoPersona extends BasicEntity
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
     
    /**
     * @ORM\ManyToOne(targetEntity="ClaseRecurso", inversedBy="recursopersona")
     * @ORM\JoinColumn(name="claserecursopersona_id", referencedColumnName="id")
     * @Assert\Type("AppBundle\Entity\ClaseRecurso")
     * @GRID\Column(field="claserecurso.descripcion", title="Clase Recurso", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $claserecurso;

    
    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * @GRID\Column(title="DNI", operatorsVisible=false)
    * @Assert\Length(
     *      max = 10,
     *      maxMessage = "DNI no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $dni;

    /**
     * @ORM\Column(type="string", length=50)
     * @GRID\Column(title="Nombre", operatorsVisible=false)
    * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Nombre no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $nombre;
    
    /**
     * @ORM\Column(type="string", length=50)
     * @GRID\Column(title="Apellido", operatorsVisible=false)
    * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Apellido no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $apellido;
    

     /**
     * @ORM\Column(type="string", length=50)
     * @GRID\Column(title="Profesion", operatorsVisible=false)
    * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Apellido no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $profesion;
    
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
    *  @GRID\Column(title="Fecha Alta", operatorsVisible=false)
     */
    protected $fechaAlta;

    /**
     * @ORM\ManyToOne(targetEntity="EstadoTabla")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
    *  @GRID\Column(field="estado.name", title="Estado", operatorsVisible=false, filter="select", selectFrom="source")
    */
    protected $estado;
    



    public function __construct()
    {
        $this->setFechaAlta(new \DateTime());
         $this->gruposRecurso = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return ($this->dni != null)? $this->dni : "";
    }

    public function getNombreCompleto () {
        return $apellido. ", " . $nombre;
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
     * Set dni
     *
     * @param string $dni
     * @return RecursoPersona
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return RecursoPersona
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return RecursoPersona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set profesion
     *
     * @param string $profesion
     * @return RecursoPersona
     */
    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get profesion
     *
     * @return string 
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set regimen
     *
     * @param string $regimen
     * @return RecursoPersona
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
     * @return RecursoPersona
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
     * @return RecursoPersona
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
     * @return RecursoPersona
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
