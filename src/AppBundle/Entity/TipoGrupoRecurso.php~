<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoGrupoRecurso
 *
 * @author Jose
 */
namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="tipogruporecurso")
 * @GRID\Source(columns="id, name")
 * @UniqueEntity("name")
 */
class TipoGrupoRecurso
{
    /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
    * @GRID\Column(field="id", title="Identificador", visible = false)
*/
    protected $id;
    /**
     * @ORM\Column(type="string", length=40)
     *  @GRID\Column(title="Nombre", operatorsVisible=false)
    */
    protected $name;


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
     * @return TipoGrupoRecurso
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
}
