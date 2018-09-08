<?php

namespace AppBundle\Repository;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends \Doctrine\ORM\EntityRepository{

	/**
     * Adiciona filtros para selecionar apenas registros ativos à query.
     * @param QueryBuilder $qb Query inicial.
     * @return QueryBuilder Query com filtros.
     */
    public function addActiveQuery(QueryBuilder $qb = null) {
        $em = $this->getEntityManager();

        if (is_null($qb)) {
            $qb = $em->createQueryBuilder();
        }

        $qb->select(array('c'))
            ->from('AppBundle:Client', 'c');

        return $qb;
    }

  	/**
     * Retorna o total de registros com o slug especificado.
     * @param string $username Username a ser pesquisado.
     * @param boolean $onlyActive Indica se deve contar apenas eventos ativos.
     * @param string $email Email a ser pesquisado
     * @return integer Total de registros.
     */
    public function getUserByNameAndUsername($entity, $onlyActive = false) {
        $qb = $this->addActiveQuery();

        $qb->andWhere("c.username = :username")
           ->setParameter("username", $entity->getUsername());

        $qb->orWhere("c.email = :email")
           ->setParameter("email", $entity->getEmail());

        return $this->validateUsersByNameAndUsername($qb,$entity);

    }

    /**
     * Complemento do método getUserByNameAndUsername
     * @param object $entity
     * @return Boolean
     */
    public function validateUsersByNameAndUsername($qb,$entity)
    {
        $flag = true;

        if ($entity->getId() != null) {
            // Buscar lista de ids com username ou email
            $results = $qb->select('c.id')->getQuery()->getScalarResult();
            $ids = array_map('current', $results);

            // Caso a lista tiver mais de 1 elemento, retorne false
            if (count($ids) > 1) { $flag = false; }
            // Caso a lista conter um elemento
            else if (count($ids) == 1 ) {
                //e esse elemento for diferente ao id de cadastro retorne false
                if (!in_array($entity->getId(), $ids)) { $flag = false; };
            }
        }
        else{
            $query = $qb->getQuery();
            if (count($query->getResult()) > 0) { $flag = false; }
        }

        return $flag;
    }
}