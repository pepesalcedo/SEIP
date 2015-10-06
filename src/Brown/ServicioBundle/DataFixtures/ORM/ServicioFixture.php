<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 25/09/2015
 * Time: 09:34 AM
 */

namespace Brown\ServicioBundle\DataFixtures\ORM;


use Brown\ServicioBundle\Entity\Servicio;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ServicioFixture extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $fileName = 'servicios.json';
        $file = 'data/' . $fileName;
        $fileContent = file_get_contents($file);
        $jsonData = json_decode($fileContent);
        foreach ($jsonData as $data)
        {
            $servicio = new Servicio();
            $servicio->setNombre($data->nombre);
            $servicio->setDescripcion($data->descripcion);
            $servicio->setEstado(constant("\\Brown\\ServicioBundle\\Entity\\Servicio::" . $data->estado));
            $servicio->setUrl($data->url);
            $manager->persist($servicio);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }


}