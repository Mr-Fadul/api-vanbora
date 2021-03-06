<?php

namespace AppBundle\Repository;

/**
 * AdRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdRepository extends \Doctrine\ORM\EntityRepository{

	public function addActiveQuery($active = true, QueryBuilder $qb = null)
    {
        $em = $this->getEntityManager();

        if (is_null($qb)) {
            $qb = $em->createQueryBuilder();
        }

        $qb->select(array('a'))
            ->from('AppBundle:Ad', 'a');
        //Verifica se o anúncio está ativo
        $qb->andWhere("a.active = :active")->setParameter("active", $active);

        return $qb;
    }

	public function findForSearch($query)
    {
        $query = str_replace("Ç", "c", $query);
        $query = str_replace("ç", "c", $query);
        $query = str_replace("ã", "a", $query);
        $query = str_replace("Ã", "A", $query);
        $query = str_replace("Í", "i", $query);
        $query = str_replace("í", "i", $query);
        $qb = $this->addActiveQuery();

        $qb->andWhere(
            $qb->expr()->like($qb->expr()->lower('a.name'), ':query') ." or ".
            $qb->expr()->like($qb->expr()->lower('a.description'), ':query') . " or ".
            $qb->expr()->like($qb->expr()->lower('a.short_description'), ':query')
        )
        ->setParameter(':query', "%" . strtolower($query) . "%");

        $qb->orderBy('a.name', 'ASC');

        return $qb->getQuery()->getResult();
    }
}