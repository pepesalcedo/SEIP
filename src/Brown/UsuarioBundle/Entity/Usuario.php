<?php

namespace Brown\UsuarioBundle\Entity;

use Brown\MunicipioBundle\Entity\Localidad;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Brown\UsuarioBundle\Entity\Role;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Brown\UsuarioBundle\Entity\Repository\UsuarioRepository")
 * @UniqueEntity(fields="email", message="Ya existe un usuario con esa dirección de correo electrónico")
 * @UniqueEntity(fields="dni", message="Ya existe un usuario con ese DNI")
 */
class Usuario implements AdvancedUserInterface
{

    const PASSWD_MIN_LENGTH = 6;
    const PASSWD_MAX_LENGTH = 12;

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
     * @ORM\Column(name="dni", type="string", length=255, unique=true)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Brown\UsuarioBundle\Entity\Role")
     * @ORM\JoinTable(name="usuarios_roles", joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $permisos;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Brown\ServicioBundle\Entity\Servicio", inversedBy="usuariosBloqueados")
     * @ORM\JoinTable(name="servicios_usuarios_bloqueados")
     */
    private $serviciosBloqueados;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="fecha_de_alta")
     */
    private $fechaDeAlta;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="activo")
     */
    private $activo;

    /**
     * @var string
     * @ORM\Column(type="string", name="calle")
     */
    private $calle;

    /**
     * @var string
     * @ORM\Column(type="string", name="calle_numero")
     */
    private $calleNumero;

    /**
     * @var string
     * @ORM\Column(type="string", name="entre_calles", nullable=true)
     */
    private $entreCalles;

    /**
     * @var string
     * @ORM\Column(type="string", name="piso", nullable=true)
     */
    private $piso;

    /**
     * @var string
     * @ORM\Column(type="string", name="departamento", nullable=true)
     */
    private $departamento;

    /**
     * @var string
     * @ORM\Column(type="string", name="codigoPostal")
     */
    private $codigoPostal;

    /**
     * @var Localidad
     * @ORM\ManyToOne(targetEntity="\Brown\MunicipioBundle\Entity\Localidad", inversedBy="usuarios")
     */
    private $localidad;

    /**
     * @var string
     * @ORM\Column(type="string", name="telefono", nullable=true)
     */
    private $telefono;

    /**
     * @var string
     * @ORM\Column(type="string", name="celular", nullable=true)
     */
    private $celular;

    /**
     * @var string
     * @ORM\Column(type="string", name="partida", nullable=true)
     */
    private $partida;

    /**
     * @var string
     * @ORM\Column(type="string", name="nacionalidad", nullable=true)
     */
    private $nacionalidad;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="fecha_de_nacimiento")
     */
    private $fechaDeNacimiento;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Brown\UsuarioBundle\Entity\Clave", mappedBy="usuario")
     */
    private $claves;

    /**
     * @var int
     * @ORM\Column(type="integer", name="login_attempts")
     */
    private $loginAttempts;

    /**
     * Usuario constructor.
     */
    public function __construct()
    {
        $this->setPermisos(new ArrayCollection());
        $this->setServiciosBloqueados(new ArrayCollection());
        $this->setClaves(new ArrayCollection());
        $this->setFechaDeAlta(new \DateTime());
        $this->generateSalt();
        $this->setActivo(true);
        $this->setLoginAttempts(0);
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
     * @return Usuario
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
     * @return Usuario
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
     * @return Usuario
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
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param bool|false $objects
     * @return array|ArrayCollection
     */
    public function getRoles()
    {
        $roles = array();
        foreach ($this->getPermisos() as $role)
        {
            /* @var $role Role */
            $roles[] = $role->getCodigo();
        }
        return $roles;
    }

    public function getUsername()
    {
        return $this->getDni();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function addRole(Role $role)
    {
        $this->getPermisos()->add($role);
    }

    /**
     * @return ArrayCollection
     */
    public function getServiciosBloqueados()
    {
        return $this->serviciosBloqueados;
    }

    /**
     * @param ArrayCollection $serviciosBloqueados
     */
    public function setServiciosBloqueados($serviciosBloqueados)
    {
        $this->serviciosBloqueados = $serviciosBloqueados;
    }

    /**
     * @return \DateTime
     */
    public function getFechaDeAlta()
    {
        return $this->fechaDeAlta;
    }

    /**
     * @param \DateTime $fechaDeAlta
     */
    public function setFechaDeAlta($fechaDeAlta)
    {
        $this->fechaDeAlta = $fechaDeAlta;
    }

    function __toString()
    {
        $nombre = $this->getNombre();
        $apellido = $this->getApellido();
        $nombreCompleto = $apellido. ', ' . $nombre;
        return $nombreCompleto;
    }

    public function generateSalt()
    {
        $salt = md5(time());
        $this->setSalt($salt);
    }

    /**
     * @return ArrayCollection
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * @param ArrayCollection $permisos
     */
    public function setPermisos($permisos)
    {
        $this->permisos = $permisos;
    }


    public function isEnabled()
    {
        return $this->isActivo();
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function isActivo()
    {
        return $this->activo;
    }

    /**
     * @param boolean $activo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    /**
     * @return string
     */
    public static function generateRandomPassword($length = 8) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * @return string
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * @param string $calle
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;
    }

    /**
     * @return string
     */
    public function getCalleNumero()
    {
        return $this->calleNumero;
    }

    /**
     * @param string $calleNumero
     */
    public function setCalleNumero($calleNumero)
    {
        $this->calleNumero = $calleNumero;
    }

    /**
     * @return string
     */
    public function getEntreCalles()
    {
        return $this->entreCalles;
    }

    /**
     * @param string $entreCalles
     */
    public function setEntreCalles($entreCalles)
    {
        $this->entreCalles = $entreCalles;
    }

    /**
     * @return string
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * @param string $piso
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;
    }

    /**
     * @return string
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param string $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return string
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * @param string $codigoPostal
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;
    }

    /**
     * @return Localidad
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * @param Localidad $localidad
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param string $celular
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    }

    /**
     * @return string
     */
    public function getPartida()
    {
        return $this->partida;
    }

    /**
     * @param string $partida
     */
    public function setPartida($partida)
    {
        $this->partida = $partida;
    }

    /**
     * @return string
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    /**
     * @param string $nacionalidad
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;
    }

    /**
     * @return string
     */
    public function getFechaDeNacimiento()
    {
        return $this->fechaDeNacimiento;
    }

    /**
     * @param \DateTime|null $fechaDeNacimiento
     */
    public function setFechaDeNacimiento($fechaDeNacimiento)
    {
        $this->fechaDeNacimiento = $fechaDeNacimiento;
    }

    /**
     * @return ArrayCollection
     */
    public function getClaves()
    {
        return $this->claves;
    }

    /**
     * @param ArrayCollection $claves
     */
    public function setClaves($claves)
    {
        $this->claves = $claves;
    }

    /**
     * @return int
     */
    public function getLoginAttempts()
    {
        return $this->loginAttempts;
    }

    /**
     * @param int $loginAttempts
     */
    public function setLoginAttempts($loginAttempts)
    {
        $this->loginAttempts = $loginAttempts;
    }

}
