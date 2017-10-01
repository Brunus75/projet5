<?php

namespace NaoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use NaoBundle\Entity\Observation;

/**
 * ObservationRepository
 *
 * Cette classe a été générée par ORM de Doctrine. Ajoutez votre propre coutume
 * Méthodes de dépôt ci-dessous.
 */
class ObservationRepository extends EntityRepository
{
    /**
     * Méthode pour obtenir toutes les observations (pour un utilisateur connecté)
     * @param $userId
     * @return array
     */
    public function findByIdUserWithEspeces($userId)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(9)
            ->setFirstResult(0)
            ->leftJoin('o.bird', 's')
            ->addSelect('s')
            ->leftJoin('o.picture', 'p')
            ->addSelect('p')
            ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Méthode d'observation avec pagination
     * @param $userId
     * @param $incre
     * @return array
     */
    public function findMoreByIdUserWithEspeces($userId, $incre)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(9)
            ->setFirstResult($incre*9)
            ->leftJoin('o.bird', 's')
            ->addSelect('s')
            ->leftJoin('o.picture', 'p')
            ->addSelect('p')
            ->leftJoin('o.user', 'u')
            ->addSelect('u');

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Méthode pour obtenir les 3 dernières observations
     * @return array
     */
    public function findLastObservations(){
        $qd = $this->createQueryBuilder('o');
        $statut = 'accepté';

        $qd
            ->where('o.statut = :statut')
            ->setParameter('statut',$statut)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(3);

        return $qd
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Observation $observation
     * @param bool $flush
     */
    public function add(Observation $observation, $flush = true)
    {

        $this->getEntityManager()->persist($observation);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Méthode pour obtenir toutes les observations par espèce
     * @param $birdId
     * @return array
     */
    public function findWithBirdName($birdId, $statut){
        $qb = $this->createQueryBuilder('o');

        $qb ->where('o.bird = :birdId')
            ->setParameter('birdId', $birdId)
            ->andWhere('o.statut = :statut')
            ->setParameter('statut', $statut)
            ->orderBy('o.date', 'desc')
            ->leftJoin('o.bird', 's')
            ->addSelect('s')
            ->leftJoin('o.user', 'u')
            ->addSelect('u')
            ->leftJoin('o.picture', 'p')
            ->addSelect('p')
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Méthode pour obtenir toutes les observations amateurs à valider
     * @param $page
     * @return QueryBuilder
     */
    public function findObservationsToValidate($page){
        $qb = $this->createQueryBuilder('o');

        $toValidate = "en attente";

        $qb
            ->where('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(10)
            ->setFirstResult($page * 10 - 10)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * Méthode pour connaître le nombre
     * @return array
     */
    public function howManyObservationsToValidate()
    {
        $qb = $this->createQueryBuilder('o');

        $toValidate = "en attente";

        $qb
            ->where('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * Méthode pour obtenir toutes les observations amateurs à valider
     * @param $observationId
     */
    public function deleteAnObservation($observationId){
        $qb = $this->createQueryBuilder('o');
        $qb
            ->delete()
            ->where('o.id = :observationId')
            ->setParameter('observationId', $observationId)
            ;

        $qb->getQuery()->getResult();
    }
}
