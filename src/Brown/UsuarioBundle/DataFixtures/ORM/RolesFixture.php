<?php
/**
 * Created by PhpStorm.
 * User: Agust�n Houlgrave
 * Date: 31/08/2015
 * Time: 09:52 AM
 */

namespace Brown\UsuarioBundle\DataFixtures\ORM;


use Brown\UsuarioBundle\Entity\Role;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RolesFixture extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $roles = json_decode(file_get_contents("data/roles.json"));
        foreach ($roles as $role)
        {
            $rol = new Role();
            $rol->setNombre($role->nombre);
            $rol->setCodigo($role->codigo);
            $manager->persist($rol);
            $manager->flush();
            $this->setReference($role->codigo, $rol);
        }
    }

    public function getOrder()
    {
        return 2;
    }

}