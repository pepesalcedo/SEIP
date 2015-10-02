<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CentroAtencion
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
 * @ORM\Table(name="centroatencion") 
 *  * @UniqueEntity(
 *     fields={"descripcion"},
 *     message="Ya existe un centro de atención con la misma descripción"
 * )
 */
class CentroAtencion extends BasicEntity
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoCentroAtencion")
     * @ORM\JoinColumn(name="tipocentroatencion_id", referencedColumnName="id")
     *  @GRID\Column(field="tipo.name", title="Tipo Centro", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $tipo;

    /**
     * @ORM\Column(type="string", length=15, nullable=false)
     * @GRID\Column(field="nombre", title="Nombre", operatorsVisible=false)
    * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Nombre no puede ser más largo que {{ limit }} caracteres"
     * )
     
     */
    protected $nombre;
    
    /**
     * @ORM\Column(type="string", length=40, unique=true, nullable=false)
     * @GRID\Column(title="Descripción", operatorsVisible=false)
    * @Assert\Length(
     *      max = 40,
     *      maxMessage = "Descripción no puede ser más largo que {{ limit }} caracteres"
     * )

    */
    protected $descripcion;

    /**
     * @ORM\Column(type="string", length=50)
     * @GRID\Column(title="Calle", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 50,
     *      maxMessage = "Calle no puede ser más largo que {{ limit }} caracteres"
     * )
    */
    protected $calle;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
    *  @GRID\Column(title="Nro", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 10,
     *      maxMessage = "Nro no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $nro;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
    *  @GRID\Column(title="Entrecalles", operatorsVisible=false)
    * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Entrecalles no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $entrecalles;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
    *  @GRID\Column(title="Piso", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 10,
     *      maxMessage = "Piso no puede ser más largo que {{ limit }} caracteres"
     * )

     */
    protected $piso;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
    *  @GRID\Column(title="Dto", operatorsVisible=false)
    *  @Assert\Length(
     *      max = 10,
     *      maxMessage = "Dto no puede ser más largo que {{ limit }} caracteres"
     * )

     */
    protected $dto;

    /**
     * @ORM\ManyToOne(targetEntity="Localidad")
     * @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
     * @Assert\Type("AppBundle\Entity\Localidad", message="Debe introducir una localidad valida")
     * @Assert\NotBlank(message="Debe introducir una localidad")
     * @GRID\Column(field="localidad.name", title="Localidad", operatorsVisible=false)
     * @GRID\Column(field="localidad.provincia.name", title="Provincia", operatorsVisible=false)
     */
    protected $localidad;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
    *  @GRID\Column(title="Teléfono", operatorsVisible=false)
          */
    protected $telefono;
    
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
     * Set nombre
     *
     * @param string $nombre
     * @return CentroAtencion
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
}
