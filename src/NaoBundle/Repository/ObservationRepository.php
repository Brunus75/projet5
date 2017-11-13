<?php

namespace NaoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use NaoBundle\Entity\Observation;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
    public function trouverIdUtilisateurAvecEspeces($userId)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(9)
            ->setFirstResult(0)
            ->leftJoin('o.oiseau', 's')
            ->addSelect('s')
            ->leftJoin('o.image', 'p')
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
    public function trouverToutEspecesIdUtilisateur($userId, $incre)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(9)
            ->setFirstResult($incre*9)
            ->leftJoin('o.oiseau', 's')
            ->addSelect('s')
            ->leftJoin('o.image', 'p')
            ->addSelect('p')
            ->leftJoin('o.user', 'u')
            ->addSelect('u');

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Méthode pour obtenir les 5 dernières observations
     * @return array
     */
    public function trouverDernierObservations(){
        $qd = $this->createQueryBuilder('o');
        $statut = 'accepte';

        $qd
            ->where('o.statut = :statut')
            ->setParameter('statut',$statut)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(5);

        return $qd
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Observation $observation
     * @param bool $flush
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function ajouter(Observation $observation, $flush = true)
    {

        $this->getEntityManager()->persist($observation);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Méthode pour obtenir toutes les observations par espèces
     * @param $oiseauId
     * @return array
     */
    public function trouverAvecNomOiseau($oiseauId, $statut){
        $qb = $this->createQueryBuilder('o');

        $qb ->where('o.oiseau = :oiseauId')
            ->setParameter('oiseauId', $oiseauId)
            ->andWhere('o.statut = :statut')
            ->setParameter('statut', $statut)
            ->orderBy('o.date', 'desc')
            ->leftJoin('o.oiseau', 's')
            ->addSelect('s')
            ->leftJoin('o.user', 'u')
            ->addSelect('u')
            ->leftJoin('o.image', 'p')
            ->addSelect('p')
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Méthode pour obtenir toutes les observations par espèces Accepte
     * @param $oiseauId
     * @return array
     */
    public function trouverAvecNomOiseauAccepte($oiseauField){
        $qb = $this->createQueryBuilder('o');

        $toValidate = "accepte";

        $qb ->where('o.oiseau = :especes_id')
            ->setParameter('especes_id', $oiseauField)
            ->andWhere('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
            ->orderBy('o.date', 'desc')
            ->leftJoin('o.oiseau', 's')
            ->addSelect('s')
            ->leftJoin('o.user', 'u')
            ->addSelect('u')
            ->leftJoin('o.image', 'p')
            ->addSelect('p')
        ;

        return $qb
            ->getQuery()
            ->getArrayResult ();
    }

    /**
     * Méthode pour obtenir toutes les observations par espèces Attente
     * @param $oiseauId
     * @return array
     */
    public function trouverAvecNomOiseauAttente($oiseauField){
        $qb = $this->createQueryBuilder('o');

        $toValidate = "attente";

        $qb ->where('o.oiseau = :especes_id')
            ->setParameter('especes_id', $oiseauField)
            ->andWhere('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
            ->orderBy('o.date', 'desc')
            ->leftJoin('o.oiseau', 's')
            ->addSelect('s')
            ->leftJoin('o.user', 'u')
            ->addSelect('u')
            ->leftJoin('o.image', 'p')
            ->addSelect('p')
        ;

        return $qb
            ->getQuery()
            ->getArrayResult ();
    }


    /**
     * Méthode pour obtenir toutes les observations amateurs à valider
     * @param $page
     * @return array
     */
    public function trouverObservationsAValider($page){
        $qb = $this->createQueryBuilder('o');

        $toValidate = "attente";

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
     * Méthode pour obtenir le nombre des observations
     * @return array
     */
    public function getNbObservations()
    {
        $qb = $this->createQueryBuilder('o');
        $toValidate = "accepte";

        $qb
            ->select('COUNT(o.id)')
            ->where('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
            ;
  //          ->getQuery()
  //          ->getSingleScalarResult();

        return $qb->getQuery()->getResult();
    }

    /**
     * Méthode pour obtenir le nombbre de toutes les observations par espèces Accepte
     * @param $oiseauId
     * @return array
     */
    public function getNbObservationsAvecNomOiseauAccepte($oiseauField){
        $qb = $this->createQueryBuilder('o');

        $toValidate = "accepte";

        $qb ->select('COUNT(o.id)')
            ->where('o.oiseau = :especes_id')
            ->setParameter('especes_id', $oiseauField)
            ->andWhere('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
        ;

        return $qb->getQuery()->getResult();
    }


    /**
     * Méthode pour connaître le nombre d'observations à valider
     * @return array
     */
    public function nombreObservationAValider()
    {
        $qb = $this->createQueryBuilder('o');

        $toValidate = "attente";

        $qb
            ->where('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * Méthode pour supprimer une observation
     * @param $observationId
     */
    public function supprimerObservation($observationId){
        $qb = $this->createQueryBuilder('o');
        $qb
            ->delete()
            ->where('o.id = :observationId')
            ->setParameter('observationId', $observationId)
            ;

        $qb->getQuery()->getResult();
    }
}
