<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 09/09/2015
 * Time: 12:29 PM
 */

namespace Brown\UsuarioBundle\Entity\Repository;


use Brown\MunicipioBundle\Entity\Localidad;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class UsuarioRepository extends EntityRepository
{

    /**
     * @param null|string $q
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findListado(
        $q = null,
        $limit = null,
        $offset = null,
        $b = 0
    )
    {
        $qb = $this->getListadoQB($q);
        //$qb->addSelect('u.roles', 'r');

        $qb->join('u.permisos', 'r');
        if ($b)
        {
            $bloqueado = (($b == 1) ? true : false);
            $qb->andWhere('u.activo = :b');
            $qb->setParameter('b',$bloqueado);
        }
        $qb->orderBy('u.apellido', Criteria::ASC);
        $qb->orderBy('u.nombre', Criteria::ASC);

        if ($offset) $qb->setFirstResult($offset);
        if ($limit) $qb->setMaxResults($limit);

        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;

    }

    /**
     * @param null|string $q
     * @return mixed
     */
    public function findTotalListado($q = null, $b = 0)
    {
        $qb = $this->getListadoQB($q);
        if ($b)
        {
            $bloqueado = (($b == 1) ? false : true);
            $qb->andWhere('u.activo = :b');
            $qb->setParameter('b',$bloqueado);
        }
        $qb->select('COUNT(u.id)');
        $query = $qb->getQuery();
        $total = $query->getSingleScalarResult();
        return $total;
    }


    /**
     * @param string $q
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getListadoQB($q)
    {
        $qb = $this->createQueryBuilder('u');


        if ($q)
        {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('u.apellido', ':q'),
                $qb->expr()->like('u.nombre', ':q'),
                $qb->expr()->like('u.dni', ':q'),
                $qb->expr()->like('u.email', ':q')
            ));
            $qb->setParameter('q', '%' . $q . '%');
        }

        return $qb;
    }

    /**
     * @param $codigo
     * @param $secret
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCodigo($codigo, $secret)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where("MD5(concat(u.id,'+',:secret)) = :code");
        $qb->setParameter('code', $codigo);
        $qb->setParameter('secret', $secret);
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        return $result;
    }

    public function countByLocalidad(Localidad $localidad)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('COUNT(u.id)');
        $qb->where('u.localidad = :localidad_id');
        $qb->setParameter('localidad_id', $localidad->getId());
        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();
        return $result;
    }

}