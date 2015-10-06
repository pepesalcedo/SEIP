<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GrupoRecurso
 *
 * @author Jose
 */
namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
/**
 * @ORM\Entity
 * @ORM\Table(name="gruporecurso")
 */
class GrupoRecurso
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoGrupoRecurso")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     *  @GRID\Column(field="tipo.name", title="Tipo Grupo Recurso", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $tipo;
    /**
     * @ORM\Column(type="string", length=40)
     * @GRID\Column(title="Descripción", operatorsVisible=false)
    * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Descripción no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $descripcion;
    /**
     * @ORM\Column(type="datetime")
     * @GRID\Column(title="Fecha Inicio", operatorsVisible=false)

     */
    protected $fechaInicio;
    /**
     * @ORM\Column(type="datetime", nullable = true)
     * @GRID\Column(title="Fecha Fin", operatorsVisible=false)
     */
    protected $fechaFin;

    /**
     * @ORM\ManyToOne(targetEntity="Brown\UsuarioBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuarioAlta_id", referencedColumnName="id", nullable = true)
     */
    protected $usuarioAlta;

        /**
     * @ORM\ManyToOne(targetEntity="Brown\UsuarioBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuarioCierre_id", referencedColumnName="id", nullable = true)
     */
    protected $usuarioCierre;
    
    /**
     * @ORM\ManyToOne(targetEntity="EstadoTabla")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
    *  @GRID\Column(field="estado.name", title="Estado", operatorsVisible=false, filter="select", selectFrom="source")
     */
 
    protected $estado;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     * @GRID\Column(title="Fecha Cierre", operatorsVisible=false)
     */
    protected $fechaCierre;
    
     /**
     * @ORM\ManyToMany(targetEntity="RecursoPersona", cascade={"persist"})
     * @ORM\JoinTable(name="gruporecurso_persona",
     *      joinColumns={@ORM\JoinColumn(name="gruporecurso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="persona_id", referencedColumnName="id")}
     *      )
     **/
    protected $personas;
    
     /**
     * @ORM\ManyToMany(targetEntity="RecursoVehiculo", inversedBy="gruposRecurso", cascade={"persist"})
     * @ORM\JoinTable(name="gruporecurso_vehiculo",
     *      joinColumns={@ORM\JoinColumn(name="gruporecurso_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="vehiculo_id", referencedColumnName="id")}
     *      )
     **/
    protected $vehiculos;


    public function __construct()
    {
            $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
            $this->vehiculos = new \Doctrine\Common\Collections\ArrayCollection();
            $this->fechaInicio = new \DateTime();
            
    }
    
    public function __toString() {
        return ($this->descripcion != null)? $this->descripcion : "";
    }

    
                    
   /**
    * return if a person is in group
    * @param type $idPerson
    * @return boolean
    */
    public function isPersonInGroup($idPerson)
    {
        foreach ($this->getPersonas() as $persona)
        {
            if ($persona->getId() ==$idPerson) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * return if a vehicle is in the group
     * @param type $idVehicle
     * @return boolean
     */
    public function isVehicleInGroup($idVehicle)
    {
        foreach ($this->getVehiculos() as $vehiculo)
        {
            if ($vehiculo->getId() ==$idVehicle) {
                return true;
            }
        }
        
        return false;
    }
    
    // set list of persons to save
    public function setPersonas($personas)
    {
        $this->personas = $personas;
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return GrupoRecurso
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return GrupoRecurso
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return GrupoRecurso
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return GrupoRecurso
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set tipo
     *
     * @param \AppBundle\Entity\TipoGrupoRecurso $tipo
     * @return GrupoRecurso
     */
    public function setTipo(\AppBundle\Entity\TipoGrupoRecurso $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \AppBundle\Entity\TipoGrupoRecurso 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set usuarioAlta
     *
     * @param \Brown\UsuarioBundle\Entity\Usuario $usuarioAlta
     * @return GrupoRecurso
     */
    public function setUsuarioAlta(\Brown\UsuarioBundle\Entity\Usuario $usuarioAlta = null)
    {
        $this->usuarioAlta = $usuarioAlta;

        return $this;
    }

    /**
     * Get usuarioAlta
     *
     * @return \Brown\UsuarioBundle\Entity\Usuario 
     */
    public function getUsuarioAlta()
    {
        return $this->usuarioAlta;
    }

    /**
     * Set usuarioCierre
     *
     * @param \Brown\UsuarioBundle\Entity\Usuario $usuarioCierre
     * @return GrupoRecurso
     */
    public function setUsuarioCierre(\Brown\UsuarioBundle\Entity\Usuario $usuarioCierre = null)
    {
        $this->usuarioCierre = $usuarioCierre;

        return $this;
    }

    /**
     * Get usuarioCierre
     *
     * @return \Brown\UsuarioBundle\Entity\Usuario 
     */
    public function getUsuarioCierre()
    {
        return $this->usuarioCierre;
    }

    /**
     * Set estado
     *
     * @param \AppBundle\Entity\EstadoTabla $estado
     * @return GrupoRecurso
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
     * Add personas
     *
     * @param \AppBundle\Entity\RecursoPersona $personas
     * @return GrupoRecurso
     */
    public function addPersona(\AppBundle\Entity\RecursoPersona $personas)
    {
        $this->personas[] = $personas;

        return $this;
    }

    /**
     * Remove personas
     *
     * @param \AppBundle\Entity\RecursoPersona $personas
     */
    public function removePersona(\AppBundle\Entity\RecursoPersona $personas)
    {
        $this->personas->removeElement($personas);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Add vehiculos
     *
     * @param \AppBundle\Entity\RecursoVehiculo $vehiculos
     * @return GrupoRecurso
     */
    public function addVehiculo(\AppBundle\Entity\RecursoVehiculo $vehiculos)
    {
        $this->vehiculos[] = $vehiculos;

        return $this;
    }

    /**
     * Remove vehiculos
     *
     * @param \AppBundle\Entity\RecursoVehiculo $vehiculos
     */
    public function removeVehiculo(\AppBundle\Entity\RecursoVehiculo $vehiculos)
    {
        $this->vehiculos->removeElement($vehiculos);
    }

    /**
     * Get vehiculos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVehiculos()
    {
        return $this->vehiculos;
    }
}
