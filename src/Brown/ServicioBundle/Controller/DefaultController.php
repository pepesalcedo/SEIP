<?php

namespace Brown\ServicioBundle\Controller;

use Brown\ServicioBundle\Entity\Servicio;
use Brown\UsuarioBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function misServiciosAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $serviciosRepo = $em->getRepository('ServicioBundle:Servicio');
        $servicios = $serviciosRepo->findBy(array(
            'estado' => Servicio::ESTADO_HABILITADO
        ));
        $user = $this->getUser();
        /* @var $user Usuario */
        $serviciosBloqueados = $user->getServiciosBloqueados();
        foreach($servicios as $key => $servicio)
        {
            foreach($serviciosBloqueados as $servicioBloqueado)
            {
                if ($servicio->getId() == $servicioBloqueado->getId())
                {
                    unset($servicios[$key]);
                }
            }
        }
        return $this->render('@Servicio/Default/mis-servicios.html.twig', array(
            'servicios' => $servicios
        ));
    }

}
