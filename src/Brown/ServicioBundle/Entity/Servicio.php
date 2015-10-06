<?php

namespace Brown\ServicioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Servicio
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Brown\ServicioBundle\Entity\ServicioRepository")
 */
class Servicio
{

    const ESTADO_HABILITADO = 1;
    const ESTADO_DESHABILITADO = 2;

    const ESTADO_HABILITADO_LABEL = 'Habilitado';
    const ESTADO_DESHABILITADO_LABEL = 'Deshabilitado';

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
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Brown\UsuarioBundle\Entity\Usuario", mappedBy="serviciosBloqueados")
     */
    private $usuariosBloqueados;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * Servicio constructor.
     */
    public function __construct()
    {
        $this->setUsuariosBloqueados(new ArrayCollection());
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
     * @return Servicio
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
     * Set estado
     *
     * @param int $estado
     * @return Servicio
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Servicio
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
     * Set usuariosBloqueados
     *
     * @param string $usuariosBloqueados
     * @return Servicio
     */
    public function setUsuariosBloqueados($usuariosBloqueados)
    {
        $this->usuariosBloqueados = $usuariosBloqueados;

        return $this;
    }

    /**
     * Get usuariosBloqueados
     *
     * @return string 
     */
    public function getUsuariosBloqueados()
    {
        return $this->usuariosBloqueados;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Servicio
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $estado
     * @return null|string
     */
    public static function getEstadoLabel($estado)
    {
        $labels = array(
            self::ESTADO_HABILITADO => self::ESTADO_HABILITADO_LABEL,
            self::ESTADO_DESHABILITADO => self::ESTADO_DESHABILITADO_LABEL
        );
        if (array_key_exists($estado, $labels))
            return $labels[$estado];
        return null;
    }

    /**
     * @return array
     */
    public static function getEstados()
    {
        return array(
            self::ESTADO_HABILITADO,
            self::ESTADO_DESHABILITADO
        );
    }

    function __toString()
    {
        return $this->getNombre();
    }

}
