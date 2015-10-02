<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClaseRecurso
 *
 * @author Jose
 */
namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="claserecurso")
 * @UniqueEntity("descripcion")
 */
class ClaseRecurso
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
    * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=1)
     *  @GRID\Column(field="clase", title="Clase", operatorsVisible=false, filter="select", 
     *          selectFrom="values", values={"P"="Personas","V"="Vehículos"})

    */
    protected $clase;

    /**
     * @ORM\ManyToOne(targetEntity="TipoGrupoRecurso")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     *  @GRID\Column(field="tipo.name", title="Tipo Grupo Recurso", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $tipo;

    
    /**
     * @ORM\Column(type="string", length=40, unique=true, nullable=false)
     *  @GRID\Column(title="Descripción", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 40,
     *      maxMessage = "Descripción no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $descripcion;
    
    /**
     * @ORM\ManyToOne(targetEntity="EstadoTabla")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
    *  @GRID\Column(field="estado.name", title="Estado", operatorsVisible=false, filter="select", selectFrom="source")

     */
    protected $estado;

    
    public function __construct()
    {
       $this->clase = 'P'; 
    }

    public function __toString() {
            return ($this->descripcion != null)? $this->descripcion : "";
        }
        
    public function isVehiculo() {
      return ($this->getClase() == 'V')? true : false;
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
     * Set clase
     *
     * @param string $clase
     * @return ClaseRecurso
     */
    public function setClase($clase)
    {
        $this->clase = $clase;

        return $this;
    }

    /**
     * Get clase
     *
     * @return string 
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return ClaseRecurso
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
     * Set tipo
     *
     * @param \AppBundle\Entity\TipoGrupoRecurso $tipo
     * @return ClaseRecurso
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
     * Set estado
     *
     * @param \AppBundle\Entity\EstadoTabla $estado
     * @return ClaseRecurso
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
