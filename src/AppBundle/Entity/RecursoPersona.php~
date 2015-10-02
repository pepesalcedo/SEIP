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
     * @return CentroAtencion
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
     * Set numero
     *
     * @param integer $numero
     * @return CentroAtencion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CentroAtencion
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
     * Set calle
     *
     * @param string $calle
     * @return CentroAtencion
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get calle
     *
     * @return string 
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set nro
     *
     * @param string $nro
     * @return CentroAtencion
     */
    public function setNro($nro)
    {
        $this->nro = $nro;

        return $this;
    }

    /**
     * Get nro
     *
     * @return string 
     */
    public function getNro()
    {
        return $this->nro;
    }

    /**
     * Set entrecalles
     *
     * @param string $entrecalles
     * @return CentroAtencion
     */
    public function setEntrecalles($entrecalles)
    {
        $this->entrecalles = $entrecalles;

        return $this;
    }

    /**
     * Get entrecalles
     *
     * @return string 
     */
    public function getEntrecalles()
    {
        return $this->entrecalles;
    }

    /**
     * Set piso
     *
     * @param string $piso
     * @return CentroAtencion
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get piso
     *
     * @return string 
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set dto
     *
     * @param string $dto
     * @return CentroAtencion
     */
    public function setDto($dto)
    {
        $this->dto = $dto;

        return $this;
    }

    /**
     * Get dto
     *
     * @return string 
     */
    public function getDto()
    {
        return $this->dto;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return CentroAtencion
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set tipo
     *
     * @param \AppBundle\Entity\TipoCentroAtencion $tipo
     * @return CentroAtencion
     */
    public function setTipo(\AppBundle\Entity\TipoCentroAtencion $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \AppBundle\Entity\TipoCentroAtencion 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    
    /**
     * Set localidad
     *
     * @param \AppBundle\Entity\Localidad $localidad
     * @return CentroAtencion
     */
    public function setLocalidad(\AppBundle\Entity\Localidad $localidad = null)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return \AppBundle\Entity\Localidad 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set estado
     *
     * @param \AppBundle\Entity\EstadoTabla $estado
     * @return CentroAtencion
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
     * Add gruposRecurso
     *
     * @param \AppBundle\Entity\GrupoRecurso $gruposRecurso
     * @return RecursoPersona
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
