<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="servicioficha")
 * @UniqueEntity("numero")
 * @GRID\Source(columns="id, numero, tipoServicio, fecha, movillogico.descripcion, estado.name ")
 */
class Servicio {
     /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=10, nullable = false)
     *  @GRID\Column(title="Número", operatorsVisible=false)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Número no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $numero;

    /**
     * @ORM\Column(type="string", length=1, nullable = false)
     *  @GRID\Column(title="Tipo Servicio", operatorsVisible=false)
     * @Assert\Length(
     *      max = 1,
     *      maxMessage = "Tipo Servicio no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $tipoServicio;

    /**
     * @ORM\Column(type="datetime", nullable = false)
     *  @GRID\Column(title="Fecha", operatorsVisible=false)
     */
    protected $fecha;
    /**
     * @ORM\ManyToOne(targetEntity="EstadoTabla")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id", nullable = true)
    *  @GRID\Column(field="estado.name", title="Estado", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $estado;

    
    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Teléfono no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $telefono;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Length(
     *      max = 1,
     *      maxMessage = "Bomberos no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $bomberos;

    
    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Length(
     *      max = 1,
     *      maxMessage = "Cobertura no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $cobertura;
    
     /** Motivo, hay que mostrar el nombre y el tipo, dependiendo de los 2 primeros caracteres del código
      * TODO, de momento muestro el código hay que transformarlo a colores, y ponerle el filtro en función de eso
     * @ORM\ManyToOne(targetEntity="Motivo")
     * @ORM\JoinColumn(name="motivo_id", referencedColumnName="id")
     *  @GRID\Column(field="motivo.name", title="Motivo", operatorsVisible=false, filter="select", selectFrom="source")
     *  @GRID\Column(field="motivo.codigo", title="Codigo Motivo", operatorsVisible=false)
     */
    protected $motivo;
    
    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Motivo Inicial no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $motivoInicial;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Calle no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $calle;

    /**
     * @ORM\Column(type="string", length=15, nullable = true)
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "Nro no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $nro;
    
    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Entrecalles no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $entrecalles;
    
    /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Piso no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $piso;
    
    /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Dto no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $dto;
    
    /**
     * @ORM\Column(type="string", length=10, nullable = true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Monoblock no puede ser más largo que {{ limit }} caracteres"
     * )
     */
    protected $monoblock;
    
    /**
     * @ORM\Column(type="string", length=50, nullable = true)
      @Assert\Length(
     *      max = 50,
     *      maxMessage = "Barrio no puede ser más largo que {{ limit }} caracteres"
     * )   
     */
    protected $barrio;

    
    /**
     * @ORM\ManyToOne(targetEntity="Localidad")
     * @ORM\JoinColumn(name="localidad_id", referencedColumnName="id", nullable=true)
     */
    protected $localidad;
    
    /**
     * @ORM\Column(type="string", length=100, nullable = true)
     @Assert\Length(
     *      max = 100,
     *      maxMessage = "Referencia no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $referencia;

    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaLlamado;
    
    /**
     * @ORM\ManyToOne(targetEntity="GrupoRecurso")
     * @ORM\JoinColumn(name="movillogico_id", referencedColumnName="id")
    *  @GRID\Column(field="movillogico.descripcion", title="Movil Lógico", operatorsVisible=false, filter="select", selectFrom="source")
     */
    protected $movillogico;
    
    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaDespacho;
    
    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaSalidaBase;

    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaLlegadaDestino;
    
    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaSalidaDestino;
    
        /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaLlegadaHospital;

    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaSalidaHospital;

    
    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaLlegadaBase;

    /**
     * @ORM\Column(type="time", nullable = true)
     */
    protected $horaDisponible;
    
        /**
     * @ORM\ManyToOne(targetEntity="IngresoLlamado")
     * @ORM\JoinColumn(name="ingresollamado_id", referencedColumnName="id")
     */
    protected $ingresoLlamado;
    
        /**
     * @ORM\ManyToOne(targetEntity="DemoraDespacho")
     * @ORM\JoinColumn(name="demoradespacho_id", referencedColumnName="id")
     */
    protected $demoraDespacho;


    
    /**
     * @ORM\ManyToOne(targetEntity="CentroAtencion")
     * @ORM\JoinColumn(name="centroatencion_id", referencedColumnName="id")
     */
    protected $centroAtencion;

    /**
     * @ORM\Column(type="string", length=100, nullable = true)
     @Assert\Length(
     *      max = 100,
     *      maxMessage = "Medico que solicita no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $medicoSolicita;

    /**
     * @ORM\Column(type="string", length=500, nullable = true)
     @Assert\Length(
     *      max = 500,
     *      maxMessage = "Observaciones no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="CentroAtencion")
     * @ORM\JoinColumn(name="centroatenciontraslado_id", referencedColumnName="id")
     */
    protected $centroAtencionTraslado;

    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     @Assert\Length(
     *      max = 50,
     *      maxMessage = "Sector no puede ser más largo que {{ limit }} caracteres"
     * ) 
     */
    protected $sector;

    /**
     * @ORM\ManyToOne(targetEntity="DestinoFinal")
     * @ORM\JoinColumn(name="destionfinal_id", referencedColumnName="id")
     */
    protected $destinoFinal;
    
    /**
     * @ORM\Column(type="string", length=100, nullable = true)
     @Assert\Length(
     *      max = 100,
     *      maxMessage = "Medico que recibe no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $medicoRecibe;
    
    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     @Assert\Length(
     *      max = 50,
     *      maxMessage = "Tribunal no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $tribunal;

    /**
     * @ORM\Column(type="string", length=50, nullable = true)
     @Assert\Length(
     *      max = 50,
     *      maxMessage = "Caratula no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $caratula;
    
     /**
     * @ORM\Column(type="string", length=50, nullable = true)
     @Assert\Length(
     *      max = 50,
     *      maxMessage = "Causa/Expediente no puede ser más largo que {{ limit }} caracteres"
     * )  
     */
    protected $causa;

    /**
     * @ORM\OneToMany(targetEntity="ServicioPaciente", mappedBy="servicio")
     */
    protected $pacientes;
    
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
    

    
    public function __construct()
    {
        //$this->numero = Servicio::calculateNextNumber(); // Default value for column is_visible
        $this->fecha =  new DateTime();
        $this->tipoServicio = 'D';
        $this->telefono = "";
        $this->bomberos = 'N';
        $this->cobertura = 'N';
        $this->pacientes = new \Doctrine\Common\Collections\ArrayCollection();

        
    }
    
    public function __toString() {
        return ($this->nombre != null)? $this->nombre . $this->apellido : "";
    }
    
       /**
    * return if a pacient is in group
    * @param type $idPerson
    * @return boolean
    */
    public function isPacienteInGroup($idPaciente)
    {
        foreach ($this->getPacientes() as $paciente)
        {
            if ($paciente->getId() ==$idPaciente) {
                return true;
            }
        }
        
        return false;
    }
    

    public function deletePacienteInGroup($idPaciente)
    {

        $pacientes = $this->getPacientes();
                
        foreach($pacientes as $personInTask)
        {
            if ($personInTask->getId() == $idPaciente)
            {
                $this->removePaciente($personInTask);
            }
        }
    }
    
    public function modifyOrAddPaciente($paciente)
    {

        foreach($this->pacientes as $index => $personInTask)
        {
            if ($personInTask->getId() == $paciente->getId())
            {
                $this->pacientes[$index] = $paciente;
                //$personInTask->setNombre($paciente->getNombre());
                //$personInTask->setNombre('lalala');
                return;
                //$this->removePaciente($personInTask);
            }
        }
        
        $this->addPaciente($paciente);
    }
    
    
   /**
    *  Calculate next number in field "numero" two letters - three numbers
    * @param type $repository
    * @return string
    */
    public function calculateNextNumber($repository)
    {
        $query = $repository->createQueryBuilder('s');
        $query->select('MAX(s.numero) AS max_numero');

        $nuevoNumero = "AA001";
        $max = $query->getQuery()->getResult();
        if ($max && count($max) > 0)
        {
            $registro = $max[0];
            $max_numero = $registro['max_numero'];
            if ($max_numero)
            {   
                $strNumero = substr($max_numero, 2);
                $numero = intval($strNumero);
                $numero ++;
                //$numero = 1000;
                if ($numero < 1000)
                {
                    $nuevoNumero = substr($max_numero, 0, 2) . str_pad(strval($numero), 3, "0", STR_PAD_LEFT);
                }
                else 
                {
                    $letra = $max_numero[1];
                    if ($letra < 'Z')
                    {
                        $letra++;
                        $nuevoNumero = substr($max_numero, 0, 1) . $letra . "001"; 
                    }
                    else
                    {
                        $letra = $max_numero[0];
                        if ($letra < 'Z')
                        {
                          $letra++;
                          $nuevoNumero = $letra . "A001";
                        }
                    }
                
                }
            }
        }
        
        $this->setNumero($nuevoNumero);
        
        return  $nuevoNumero;
    }
    
    
    /**
     * return next paciente
     * @param type $idPaciente
     * @return type
     */
    public function getNextPaciente($idPaciente)
    {
        $bNext = false;
        foreach ($this->pacientes as $paciente)
        {
            if ($bNext == true)
            {
                return $paciente;
            }
            // Cuando lo encuentro devuelvo el siguiente
            if ($paciente->getId() == $idPaciente)
            {
                $bNext = true; 
            }
        }
        
        return null;
        
    }
    
    /**
     * return previous paciente
     * @param type $idPaciente
     * @return type
     */
    public function getPreviousPaciente($idPaciente)
    {
        $previous = null;
        foreach ($this->pacientes as $paciente)
        {
            // Cuando lo encuentro devuelvo el que tengo guardado anterior
            if ($paciente->getId() == $idPaciente)
            {
                return $previous; 
            }
            
            $previous = $paciente;
        }
        
        return null;
        
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
     * Set numero
     *
     * @param string $numero
     * @return Servicio
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tipoServicio
     *
     * @param string $tipoServicio
     * @return Servicio
     */
    public function setTipoServicio($tipoServicio)
    {
        $this->tipoServicio = $tipoServicio;

        return $this;
    }

    /**
     * Get tipoServicio
     *
     * @return string 
     */
    public function getTipoServicio()
    {
        return $this->tipoServicio;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Servicio
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Servicio
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
     * Set bomberos
     *
     * @param string $bomberos
     * @return Servicio
     */
    public function setBomberos($bomberos)
    {
        $this->bomberos = $bomberos;

        return $this;
    }

    /**
     * Get bomberos
     *
     * @return string 
     */
    public function getBomberos()
    {
        return $this->bomberos;
    }

    /**
     * Set cobertura
     *
     * @param string $cobertura
     * @return Servicio
     */
    public function setCobertura($cobertura)
    {
        $this->cobertura = $cobertura;

        return $this;
    }

    /**
     * Get cobertura
     *
     * @return string 
     */
    public function getCobertura()
    {
        return $this->cobertura;
    }

    /**
     * Set calle
     *
     * @param string $calle
     * @return Servicio
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
     * @return Servicio
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
     * @return Servicio
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
     * @return Servicio
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
     * @return Servicio
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
     * Set monoblock
     *
     * @param string $monoblock
     * @return Servicio
     */
    public function setMonoblock($monoblock)
    {
        $this->monoblock = $monoblock;

        return $this;
    }

    /**
     * Get monoblock
     *
     * @return string 
     */
    public function getMonoblock()
    {
        return $this->monoblock;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
     * @return Servicio
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string 
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     * @return Servicio
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set horaLlamado
     *
     * @param \DateTime $horaLlamado
     * @return Servicio
     */
    public function setHoraLlamado($horaLlamado)
    {
        $this->horaLlamado = $horaLlamado;

        return $this;
    }

    /**
     * Get horaLlamado
     *
     * @return \DateTime 
     */
    public function getHoraLlamado()
    {
        return $this->horaLlamado;
    }

    /**
     * Set horaDespacho
     *
     * @param \DateTime $horaDespacho
     * @return Servicio
     */
    public function setHoraDespacho($horaDespacho)
    {
        $this->horaDespacho = $horaDespacho;

        return $this;
    }

    /**
     * Get horaDespacho
     *
     * @return \DateTime 
     */
    public function getHoraDespacho()
    {
        return $this->horaDespacho;
    }

    /**
     * Set horaSalidaBase
     *
     * @param \DateTime $horaSalidaBase
     * @return Servicio
     */
    public function setHoraSalidaBase($horaSalidaBase)
    {
        $this->horaSalidaBase = $horaSalidaBase;

        return $this;
    }

    /**
     * Get horaSalidaBase
     *
     * @return \DateTime 
     */
    public function getHoraSalidaBase()
    {
        return $this->horaSalidaBase;
    }

    /**
     * Set horaLlegadaDestino
     *
     * @param \DateTime $horaLlegadaDestino
     * @return Servicio
     */
    public function setHoraLlegadaDestino($horaLlegadaDestino)
    {
        $this->horaLlegadaDestino = $horaLlegadaDestino;

        return $this;
    }

    /**
     * Get horaLlegadaDestino
     *
     * @return \DateTime 
     */
    public function getHoraLlegadaDestino()
    {
        return $this->horaLlegadaDestino;
    }

    /**
     * Set horaSalidaDestino
     *
     * @param \DateTime $horaSalidaDestino
     * @return Servicio
     */
    public function setHoraSalidaDestino($horaSalidaDestino)
    {
        $this->horaSalidaDestino = $horaSalidaDestino;

        return $this;
    }

    /**
     * Get horaSalidaDestino
     *
     * @return \DateTime 
     */
    public function getHoraSalidaDestino()
    {
        return $this->horaSalidaDestino;
    }

    /**
     * Set horaLlegadaHospital
     *
     * @param \DateTime $horaLlegadaHospital
     * @return Servicio
     */
    public function setHoraLlegadaHospital($horaLlegadaHospital)
    {
        $this->horaLlegadaHospital = $horaLlegadaHospital;

        return $this;
    }

    /**
     * Get horaLlegadaHospital
     *
     * @return \DateTime 
     */
    public function getHoraLlegadaHospital()
    {
        return $this->horaLlegadaHospital;
    }

    /**
     * Set horaSalidaHospital
     *
     * @param \DateTime $horaSalidaHospital
     * @return Servicio
     */
    public function setHoraSalidaHospital($horaSalidaHospital)
    {
        $this->horaSalidaHospital = $horaSalidaHospital;

        return $this;
    }

    /**
     * Get horaSalidaHospital
     *
     * @return \DateTime 
     */
    public function getHoraSalidaHospital()
    {
        return $this->horaSalidaHospital;
    }

    /**
     * Set horaDisponible
     *
     * @param \DateTime $horaDisponible
     * @return Servicio
     */
    public function setHoraDisponible($horaDisponible)
    {
        $this->horaDisponible = $horaDisponible;

        return $this;
    }

    /**
     * Get horaDisponible
     *
     * @return \DateTime 
     */
    public function getHoraDisponible()
    {
        return $this->horaDisponible;
    }

    /**
     * Set medicoSolicita
     *
     * @param string $medicoSolicita
     * @return Servicio
     */
    public function setMedicoSolicita($medicoSolicita)
    {
        $this->medicoSolicita = $medicoSolicita;

        return $this;
    }

    /**
     * Get medicoSolicita
     *
     * @return string 
     */
    public function getMedicoSolicita()
    {
        return $this->medicoSolicita;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Servicio
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set sector
     *
     * @param string $sector
     * @return Servicio
     */
    public function setSector($sector)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return string 
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set medicoRecibe
     *
     * @param string $medicoRecibe
     * @return Servicio
     */
    public function setMedicoRecibe($medicoRecibe)
    {
        $this->medicoRecibe = $medicoRecibe;

        return $this;
    }

    /**
     * Get medicoRecibe
     *
     * @return string 
     */
    public function getMedicoRecibe()
    {
        return $this->medicoRecibe;
    }

    /**
     * Set tribunal
     *
     * @param string $tribunal
     * @return Servicio
     */
    public function setTribunal($tribunal)
    {
        $this->tribunal = $tribunal;

        return $this;
    }

    /**
     * Get tribunal
     *
     * @return string 
     */
    public function getTribunal()
    {
        return $this->tribunal;
    }

    /**
     * Set caratula
     *
     * @param string $caratula
     * @return Servicio
     */
    public function setCaratula($caratula)
    {
        $this->caratula = $caratula;

        return $this;
    }

    /**
     * Get caratula
     *
     * @return string 
     */
    public function getCaratula()
    {
        return $this->caratula;
    }

    /**
     * Set causa
     *
     * @param string $causa
     * @return Servicio
     */
    public function setCausa($causa)
    {
        $this->causa = $causa;

        return $this;
    }

    /**
     * Get causa
     *
     * @return string 
     */
    public function getCausa()
    {
        return $this->causa;
    }

    /**
     * Set estado
     *
     * @param \AppBundle\Entity\EstadoTabla $estado
     * @return Servicio
     */
    public function setEstado(\AppBundle\Entity\EstadoTabla $estado)
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
     * Set motivo
     *
     * @param \AppBundle\Entity\Motivo $motivo
     * @return Servicio
     */
    public function setMotivo(\AppBundle\Entity\Motivo $motivo = null)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return \AppBundle\Entity\Motivo 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set localidad
     *
     * @param \AppBundle\Entity\Localidad $localidad
     * @return Servicio
     */
    public function setLocalidad(\AppBundle\Entity\Localidad $localidad)
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
     * Set ingresoLlamado
     *
     * @param \AppBundle\Entity\IngresoLlamado $ingresoLlamado
     * @return Servicio
     */
    public function setIngresoLlamado(\AppBundle\Entity\IngresoLlamado $ingresoLlamado = null)
    {
        $this->ingresoLlamado = $ingresoLlamado;

        return $this;
    }

    /**
     * Get ingresoLlamado
     *
     * @return \AppBundle\Entity\IngresoLlamado 
     */
    public function getIngresoLlamado()
    {
        return $this->ingresoLlamado;
    }

    /**
     * Set demoraDespacho
     *
     * @param \AppBundle\Entity\DemoraDespacho $demoraDespacho
     * @return Servicio
     */
    public function setDemoraDespacho(\AppBundle\Entity\DemoraDespacho $demoraDespacho = null)
    {
        $this->demoraDespacho = $demoraDespacho;

        return $this;
    }

    /**
     * Get demoraDespacho
     *
     * @return \AppBundle\Entity\DemoraDespacho 
     */
    public function getDemoraDespacho()
    {
        return $this->demoraDespacho;
    }

    /**
     * Set centroAtencion
     *
     * @param \AppBundle\Entity\CentroAtencion $centroAtencion
     * @return Servicio
     */
    public function setCentroAtencion(\AppBundle\Entity\CentroAtencion $centroAtencion = null)
    {
        $this->centroAtencion = $centroAtencion;

        return $this;
    }

    /**
     * Get centroAtencion
     *
     * @return \AppBundle\Entity\CentroAtencion 
     */
    public function getCentroAtencion()
    {
        return $this->centroAtencion;
    }

    /**
     * Set centroAtencionTraslado
     *
     * @param \AppBundle\Entity\CentroAtencion $centroAtencionTraslado
     * @return Servicio
     */
    public function setCentroAtencionTraslado(\AppBundle\Entity\CentroAtencion $centroAtencionTraslado = null)
    {
        $this->centroAtencionTraslado = $centroAtencionTraslado;

        return $this;
    }

    /**
     * Get centroAtencionTraslado
     *
     * @return \AppBundle\Entity\CentroAtencion 
     */
    public function getCentroAtencionTraslado()
    {
        return $this->centroAtencionTraslado;
    }

    /**
     * Set destinoFinal
     *
     * @param \AppBundle\Entity\DestinoFinal $destinoFinal
     * @return Servicio
     */
    public function setDestinoFinal(\AppBundle\Entity\DestinoFinal $destinoFinal = null)
    {
        $this->destinoFinal = $destinoFinal;

        return $this;
    }

    /**
     * Get destinoFinal
     *
     * @return \AppBundle\Entity\DestinoFinal 
     */
    public function getDestinoFinal()
    {
        return $this->destinoFinal;
    }

    /**
     * Add pacientes
     *
     * @param \AppBundle\Entity\ServicioPaciente $pacientes
     * @return Servicio
     */
    public function addPaciente(\AppBundle\Entity\ServicioPaciente $pacientes)
    {
        $this->pacientes[] = $pacientes;

        return $this;
    }

    /**
     * Remove pacientes
     *
     * @param \AppBundle\Entity\ServicioPaciente $pacientes
     */
    public function removePaciente(\AppBundle\Entity\ServicioPaciente $pacientes)
    {
        $this->pacientes->removeElement($pacientes);
    }

    /**
     * Get pacientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPacientes()
    {
        return $this->pacientes;
    }

    /**
     * Set horaLlegadaBase
     *
     * @param \DateTime $horaLlegadaBase
     * @return Servicio
     */
    public function setHoraLlegadaBase($horaLlegadaBase)
    {
        $this->horaLlegadaBase = $horaLlegadaBase;

        return $this;
    }

    /**
     * Get horaLlegadaBase
     *
     * @return \DateTime 
     */
    public function getHoraLlegadaBase()
    {
        return $this->horaLlegadaBase;
    }

    /**
     * Set motivoInicial
     *
     * @param string $motivoInicial
     * @return Servicio
     */
    public function setMotivoInicial($motivoInicial)
    {
        $this->motivoInicial = $motivoInicial;

        return $this;
    }

    /**
     * Get motivoInicial
     *
     * @return string 
     */
    public function getMotivoInicial()
    {
        return $this->motivoInicial;
    }

    /**
     * Set movillogico
     *
     * @param \AppBundle\Entity\GrupoRecurso $movillogico
     * @return Servicio
     */
    public function setMovillogico(\AppBundle\Entity\GrupoRecurso $movillogico = null)
    {
        $this->movillogico = $movillogico;

        return $this;
    }

    /**
     * Get movillogico
     *
     * @return \AppBundle\Entity\GrupoRecurso 
     */
    public function getMovillogico()
    {
        return $this->movillogico;
    }




    /**
     * Set usuarioAlta
     *
     * @param \Brown\UsuarioBundle\Entity\Usuario $usuarioAlta
     * @return Servicio
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
     * @return Servicio
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
}
