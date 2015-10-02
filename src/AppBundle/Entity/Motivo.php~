<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Motivo
 *
 * @author Jose
 */
namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="motivo")
 * @UniqueEntity("name")
 */
class Motivo
{
     /** @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
      * @GRID\Column(field="id", title="Identificador", visible = false)
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Nombre no puede ser más largo que {{ limit }} caracteres"
     * )
     *  @GRID\Column(title="Nombre", operatorsVisible=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Codigo no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Codigo", operatorsVisible=false)
     */
    protected $codigo;
    
        /**
     * @ORM\Column(type="string", length=1, nullable=false)
     * @Assert\Length(
     *      max = 1,
     *      maxMessage = "Bomberos no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Derivado de bomberos", operatorsVisible=false)
     */
    protected $bomberos;

        /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Ficha instrucciones no puede ser más largo que {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Ficha de intrucción", operatorsVisible=false, visible = false)
     */
    protected $path;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    
     public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }
    
    public function getWebRelativePath()
    {
        return '../web/'.$this->getUploadDir(). "/" . $this->path;        
    }
    

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    


    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }
    
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
     * @return Motivo
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
     * Set codigo
     *
     * @param string $codigo
     * @return Motivo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
    
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        $strUploadDir = $this->getUploadRootDir();
// move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $strUploadDir,
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Set bomberos
     *
     * @param string $bomberos
     * @return Motivo
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
     * Set path
     *
     * @param string $path
     * @return Motivo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
}
