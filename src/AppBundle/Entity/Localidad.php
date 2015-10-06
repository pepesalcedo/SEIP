<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Localidad
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
 * @ORM\Table(name="localidadsiep")
 * @UniqueEntity("name")
 */
class Localidad
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
    * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable =false, unique = true)
     *  @GRID\Column(title="Nombre", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 255,
     *      maxMessage = "Localidad no puede ser más largo que {{ limit }} caracteres"
     * )

     */
    protected $name;


    /**
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id")
    *  @GRID\Column(field="provincia.name", title="Provincia", operatorsVisible=false, filter="select", selectFrom="source")

     */
    protected $provincia;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *  @GRID\Column(title="Partido", operatorsVisible=false)
     *  @Assert\Length(
     *      max = 50,
     *      maxMessage = "Partido no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $partido;

    

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
     * @return Localidad
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
     * Set provincia
     *
     * @param \AppBundle\Entity\Provincia $provincia
     * @return Localidad
     */
    public function setProvincia(\AppBundle\Entity\Provincia $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return \AppBundle\Entity\Provincia 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }


    /**
     * Set partido
     *
     * @param string $partido
     * @return Localidad
     */
    public function setPartido($partido)
    {
        $this->partido = $partido;

        return $this;
    }

    /**
     * Get partido
     *
     * @return string 
     */
    public function getPartido()
    {
        return $this->partido;
    }
}
