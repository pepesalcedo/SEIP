<?php
/**
 * Created by PhpStorm.
 * User: Agust�n Houlgrave
 * Date: 31/08/2015
 * Time: 09:42 AM
 */

namespace Brown\UsuarioBundle\DataFixtures\ORM;


use Brown\MunicipioBundle\Entity\Localidad;
use Brown\UsuarioBundle\Entity\Usuario;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UsuarioFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $raw = file_get_contents('data/usuarios.json');
        $usuarios = json_decode($raw);
        foreach ($usuarios as $data)
        {
            $usuario = new Usuario();
            $usuario->setNombre($data->nombre);
            $usuario->setApellido($data->apellido);
            $usuario->setDni($data->dni);
            $usuario->setEmail($data->email);
            $usuario->setActivo($data->activo);
            $localidad = $this->getReference($data->localidad);
            /* @var $localidad Localidad */
            $usuario->setLocalidad($localidad);

            //  Datos obligatorios no relevantes
            $usuario->setCalle("Calle Falsa");
            $usuario->setCalleNumero(rand(0,1500));
            $usuario->setCodigoPostal(rand(1500,1900));
            $usuario->setNacionalidad("Argentino");
            $usuario->setFechaDeNacimiento(\DateTime::createFromFormat("d/m/Y", rand(1,30) . "/" . rand(1,12) . "/" . rand(1960,1997)));

            $usuario->setSalt(md5(time()));
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($usuario);
            $rawPassword = $data->password;
            $salt = $usuario->getSalt();
            $encodedPassword = $encoder->encodePassword($rawPassword, $salt);
            $usuario->setPassword($encodedPassword);
            $roles = array();
            foreach ($data->roles as $codigo) {
                $roles[] = $this->getReference($codigo);
            }
            $usuario->setPermisos(new ArrayCollection($roles));
            $manager->persist($usuario);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}