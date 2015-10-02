<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="serviciopaciente")
 */
class ServicioPaciente {
     /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Servicio", inversedBy="pacientes")
     * @ORM\JoinColumn(name="servicio_id", referencedColumnName="id", nullable = true)
     */
    protected $servicio;

    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     *  @GRID\Column(title="Nombre", operatorsVisible=false)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Nombre no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     *  @GRID\Column(title="Apellido", operatorsVisible=false)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Apellido no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $apellido;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @GRID\Column(field="edad", title="Edad", operatorsVisible=false)
     * @Assert\Type(type="numeric", 
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    protected $edad;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Length(
     *      max = 1,
     *      maxMessage = "Tipo Edad no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $tipoEdad;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Length(
     *      max = 1,
     *      maxMessage = "Sexo no puede ser más largo que {{ limit }} caracteres"
     * )
     */   
    protected $sexo;

    /**
     * @ORM\Column(type="string", length=12, nullable = true)
    * @GRID\Column(title="DNI", operatorsVisible=false)

     * @Assert\Length(
     *      max = 12,
     *      maxMessage = "DNI no puede ser más largo que {{ limit }} caracteres"
     * )
     */   
    protected $dni;

    /**
     * @ORM\Column(type="string", length=80, nullable = true)
     * @Assert\Length(
     *      max = 80,
     *      maxMessage = "Obra social no puede ser más largo que {{ limit }} caracteres"
     * )
     */   
    protected $obraSocial;
    
     /**
     * @ORM\ManyToOne(targetEntity="Diagnostico")
     * @ORM\JoinColumn(name="diagnostico1_id", referencedColumnName="id")
     * @GRID\Column(field="diagnostico1.name", title="Diagnostico", operatorsVisible=false)

     */
    protected $diagnostico1;
    
     /**
     * @ORM\ManyToOne(targetEntity="Diagnostico")
     * @ORM\JoinColumn(name="diagnostico2_id", referencedColumnName="id")
     */
    protected $diagnostico2;
    
     /**
     * @ORM\ManyToOne(targetEntity="Diagnostico")
     * @ORM\JoinColumn(name="diagnostico3_id", referencedColumnName="id")
     */
    protected $diagnostico3;
    
         /**
     * @ORM\ManyToOne(targetEntity="Diagnostico")
     * @ORM\JoinColumn(name="diagnostico4_id", referencedColumnName="id")
     */
    protected $diagnostico4;
    
         /**
     * @ORM\ManyToOne(targetEntity="Diagnostico")
     * @ORM\JoinColumn(name="diagnostico5_id", referencedColumnName="id")
     */
    protected $diagnostico5;

    /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "FR no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="FR", operatorsVisible=false)
     */   
    protected $FR;
    
        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "FC no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="FC", operatorsVisible=false)
     */   
    protected $FC;

        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "TA no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="TA", operatorsVisible=false)
     */   
    protected $TA;
    
        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Pulso no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Pulso", operatorsVisible=false)
     */   
    protected $pulso;
    
        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Temperatura no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Temperatura", operatorsVisible=false)
     */   
    protected $temperatura;
    
        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Sat O2 no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Sat 02", operatorsVisible=false)
     */   
    protected $satO2;
    
        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "HGT no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="HGT", operatorsVisible=false)
     */   
    protected $HGT;
    
        /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Glasgow no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Glasgow", operatorsVisible=false)
     */   
    protected $glasgow;
    
     /**
     * @ORM\Column(type="string", length=1)
     * @GRID\Column(title="Embarazo", operatorsVisible=false)
     */   
    protected $embarazo;
    
    /**
      * @ORM\Column(type="string", length=1)
     * @GRID\Column(title="Controlado", operatorsVisible=false)
     */   
    protected $controlado;   
    
    /**
      * @ORM\Column(type="string", length=1)
     * @GRID\Column(title="De Riesgo", operatorsVisible=false)
     */   
    protected $deriesgo;   
    
    
    /**
      * @ORM\Column(type="integer", nullable = true)
     * @GRID\Column(title="Semanas gestación", operatorsVisible=false)
     */   
    protected $semanasgestacion;   
    
    /**
      * @ORM\Column(type="string", length=1)
     * @GRID\Column(title="Trabajo de parto", operatorsVisible=false)
     */   
    protected $trabajoparto;   
    
    
    public function getDiagnosticos()
    {
        $strDiagnostico = ($this->getDiagnostico1())? $this->getDiagnostico1()->getDescripcion() : "";
        $strDiagnostico .= ($this->getDiagnostico2())? "," . $this->getDiagnostico2()->getDescripcion() : "";
        $strDiagnostico .= ($this->getDiagnostico3())? "," . $this->getDiagnostico3()->getDescripcion() : "";
        $strDiagnostico .= ($this->getDiagnostico4())? "," . $this->getDiagnostico4()->getDescripcion() : "";
        $strDiagnostico .= ($this->getDiagnostico5())? "," . $this->getDiagnostico5()->getDescripcion() : "";
        return $strDiagnostico;
    }
    
    
    public function __construct()
    {
        $this->tipoEdad = 'A';
        $this->sexo = 'M';
        $this->embarazo = 'N';
        $this->controlado = 'N';
        $this->deriesgo = 'N';
        $this->trabajoparto = 'N';      
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
     * @return ServicioPaciente
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
     * @return ServicioPaciente
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
     * Set edad
     *
     * @param integer $edad
     * @return ServicioPaciente
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;

        return $this;
    }

    /**
     * Get edad
     *
     * @return integer 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set tipoEdad
     *
     * @param string $tipoEdad
     * @return ServicioPaciente
     */
    public function setTipoEdad($tipoEdad)
    {
        $this->tipoEdad = $tipoEdad;

        return $this;
    }

    /**
     * Get tipoEdad
     *
     * @return string 
     */
    public function getTipoEdad()
    {
        return $this->tipoEdad;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return ServicioPaciente
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set dni
     *
     * @param string $dni
     * @return ServicioPaciente
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
     * Set obraSocial
     *
     * @param string $obraSocial
     * @return ServicioPaciente
     */
    public function setObraSocial($obraSocial)
    {
        $this->obraSocial = $obraSocial;

        return $this;
    }

    /**
     * Get obraSocial
     *
     * @return string 
     */
    public function getObraSocial()
    {
        return $this->obraSocial;
    }

    /**
     * Set FR
     *
     * @param string $fR
     * @return ServicioPaciente
     */
    public function setFR($fR)
    {
        $this->FR = $fR;

        return $this;
    }

    /**
     * Get FR
     *
     * @return string 
     */
    public function getFR()
    {
        return $this->FR;
    }

    /**
     * Set FC
     *
     * @param string $fC
     * @return ServicioPaciente
     */
    public function setFC($fC)
    {
        $this->FC = $fC;

        return $this;
    }

    /**
     * Get FC
     *
     * @return string 
     */
    public function getFC()
    {
        return $this->FC;
    }

    /**
     * Set TA
     *
     * @param string $tA
     * @return ServicioPaciente
     */
    public function setTA($tA)
    {
        $this->TA = $tA;

        return $this;
    }

    /**
     * Get TA
     *
     * @return string 
     */
    public function getTA()
    {
        return $this->TA;
    }

    /**
     * Set pulso
     *
     * @param string $pulso
     * @return ServicioPaciente
     */
    public function setPulso($pulso)
    {
        $this->pulso = $pulso;

        return $this;
    }

    /**
     * Get pulso
     *
     * @return string 
     */
    public function getPulso()
    {
        return $this->pulso;
    }

    /**
     * Set temperatura
     *
     * @param string $temperatura
     * @return ServicioPaciente
     */
    public function setTemperatura($temperatura)
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    /**
     * Get temperatura
     *
     * @return string 
     */
    public function getTemperatura()
    {
        return $this->temperatura;
    }

    /**
     * Set satO2
     *
     * @param string $satO2
     * @return ServicioPaciente
     */
    public function setSatO2($satO2)
    {
        $this->satO2 = $satO2;

        return $this;
    }

    /**
     * Get satO2
     *
     * @return string 
     */
    public function getSatO2()
    {
        return $this->satO2;
    }

    /**
     * Set HGT
     *
     * @param string $hGT
     * @return ServicioPaciente
     */
    public function setHGT($hGT)
    {
        $this->HGT = $hGT;

        return $this;
    }

    /**
     * Get HGT
     *
     * @return string 
     */
    public function getHGT()
    {
        return $this->HGT;
    }

    /**
     * Set glasgow
     *
     * @param string $glasgow
     * @return ServicioPaciente
     */
    public function setGlasgow($glasgow)
    {
        $this->glasgow = $glasgow;

        return $this;
    }

    /**
     * Get glasgow
     *
     * @return string 
     */
    public function getGlasgow()
    {
        return $this->glasgow;
    }

    /**
     * Set embarazo
     *
     * @param string $embarazo
     * @return ServicioPaciente
     */
    public function setEmbarazo($embarazo)
    {
        $this->embarazo = $embarazo;

        return $this;
    }

    /**
     * Get embarazo
     *
     * @return string 
     */
    public function getEmbarazo()
    {
        return $this->embarazo;
    }

    /**
     * Set controlado
     *
     * @param string $controlado
     * @return ServicioPaciente
     */
    public function setControlado($controlado)
    {
        $this->controlado = $controlado;

        return $this;
    }

    /**
     * Get controlado
     *
     * @return string 
     */
    public function getControlado()
    {
        return $this->controlado;
    }

    /**
     * Set deriesgo
     *
     * @param string $deriesgo
     * @return ServicioPaciente
     */
    public function setDeriesgo($deriesgo)
    {
        $this->deriesgo = $deriesgo;

        return $this;
    }

    /**
     * Get deriesgo
     *
     * @return string 
     */
    public function getDeriesgo()
    {
        return $this->deriesgo;
    }

    /**
     * Set semanasgestacion
     *
     * @param integer $semanasgestacion
     * @return ServicioPaciente
     */
    public function setSemanasgestacion($semanasgestacion)
    {
        $this->semanasgestacion = $semanasgestacion;

        return $this;
    }

    /**
     * Get semanasgestacion
     *
     * @return integer 
     */
    public function getSemanasgestacion()
    {
        return $this->semanasgestacion;
    }

    /**
     * Set trabajoparto
     *
     * @param string $trabajoparto
     * @return ServicioPaciente
     */
    public function setTrabajoparto($trabajoparto)
    {
        $this->trabajoparto = $trabajoparto;

        return $this;
    }

    /**
     * Get trabajoparto
     *
     * @return string 
     */
    public function getTrabajoparto()
    {
        return $this->trabajoparto;
    }

    /**
     * Set servicio
     *
     * @param \AppBundle\Entity\Servicio $servicio
     * @return ServicioPaciente
     */
    public function setServicio(\AppBundle\Entity\Servicio $servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return \AppBundle\Entity\Servicio 
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set diagnostico1
     *
     * @param \AppBundle\Entity\Diagnostico $diagnostico1
     * @return ServicioPaciente
     */
    public function setDiagnostico1(\AppBundle\Entity\Diagnostico $diagnostico1 = null)
    {
        $this->diagnostico1 = $diagnostico1;

        return $this;
    }

    /**
     * Get diagnostico1
     *
     * @return \AppBundle\Entity\Diagnostico 
     */
    public function getDiagnostico1()
    {
        return $this->diagnostico1;
    }

    /**
     * Set diagnostico2
     *
     * @param \AppBundle\Entity\Diagnostico $diagnostico2
     * @return ServicioPaciente
     */
    public function setDiagnostico2(\AppBundle\Entity\Diagnostico $diagnostico2 = null)
    {
        $this->diagnostico2 = $diagnostico2;

        return $this;
    }

    /**
     * Get diagnostico2
     *
     * @return \AppBundle\Entity\Diagnostico 
     */
    public function getDiagnostico2()
    {
        return $this->diagnostico2;
    }

    /**
     * Set diagnostico3
     *
     * @param \AppBundle\Entity\Diagnostico $diagnostico3
     * @return ServicioPaciente
     */
    public function setDiagnostico3(\AppBundle\Entity\Diagnostico $diagnostico3 = null)
    {
        $this->diagnostico3 = $diagnostico3;

        return $this;
    }

    /**
     * Get diagnostico3
     *
     * @return \AppBundle\Entity\Diagnostico 
     */
    public function getDiagnostico3()
    {
        return $this->diagnostico3;
    }

    /**
     * Set diagnostico4
     *
     * @param \AppBundle\Entity\Diagnostico $diagnostico4
     * @return ServicioPaciente
     */
    public function setDiagnostico4(\AppBundle\Entity\Diagnostico $diagnostico4 = null)
    {
        $this->diagnostico4 = $diagnostico4;

        return $this;
    }

    /**
     * Get diagnostico4
     *
     * @return \AppBundle\Entity\Diagnostico 
     */
    public function getDiagnostico4()
    {
        return $this->diagnostico4;
    }

    /**
     * Set diagnostico5
     *
     * @param \AppBundle\Entity\Diagnostico $diagnostico5
     * @return ServicioPaciente
     */
    public function setDiagnostico5(\AppBundle\Entity\Diagnostico $diagnostico5 = null)
    {
        $this->diagnostico5 = $diagnostico5;

        return $this;
    }

    /**
     * Get diagnostico5
     *
     * @return \AppBundle\Entity\Diagnostico 
     */
    public function getDiagnostico5()
    {
        return $this->diagnostico5;
    }
}
