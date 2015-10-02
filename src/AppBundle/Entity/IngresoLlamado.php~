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
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="ingresollamado")
 *  @UniqueEntity("name")
 */
class IngresoLlamado
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)

     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Nombre no puede ser más largo que {{ limit }} caracteres"
     * ) 
     * @GRID\Column(title="Nombre", operatorsVisible=false)
     */
    protected $name;



    public function __construct()
    {
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
     * @return IngresoLlamado
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
