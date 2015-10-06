<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 21/09/2015
 * Time: 12:54 PM
 */

namespace Brown\MunicipioBundle\DataFixtures\ORM;


use Brown\MunicipioBundle\Entity\Localidad;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LocalidadFixture extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $file = 'data/localidades.json';
        $fileContent = file_get_contents($file);
        $data = json_decode($fileContent);
        foreach ($data as $l) {
            $nombre = $l->nombre;
            $localidad = new Localidad();
            $localidad->setNombre($nombre);
            $this->addReference($nombre, $localidad);
            $manager->persist($localidad);
        }
        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }

}