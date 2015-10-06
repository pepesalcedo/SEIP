<?php

namespace Brown\MunicipioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Localidad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Localidad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\Brown\UsuarioBundle\Entity\Usuario", mappedBy="localidad")
     */
    private $usuarios;

    /**
     * Localidad constructor.
     */
    public function __construct()
    {
        $this->setUsuarios(new ArrayCollection());
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
     * @return Localidad
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
     * @return ArrayCollection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * @param ArrayCollection $usuarios
     */
    public function setUsuarios($usuarios)
    {
        $this->usuarios = $usuarios;
    }

    function __toString()
    {
        return $this->getNombre();
    }


}
