<?php

namespace NaoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EspecesRepository
 *
 * Cette classe a été générée par ORM de Doctrine. Ajoutez votre propre coutume
 * repository methods below.
 */
class EspecesRepository extends EntityRepository
{
    /**
     * @param string $bird
     *
     * @return array
     */
    public function findLike($bird)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.nomVern LIKE :nomVern')
            ->setParameter('nomVern', "%$bird%")
            ->orderBy('a.nomVern')
            ->setMaxResults(20)
            ->getQuery()
            ->execute()
            ;
    }

    public function getLikeQueryBuilder($pattern)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.nomVern LIKE :pattern')
            ->setParameter('pattern', $pattern)
            ;
    }

    /**
     * Méthode pour obtenir tous les "Ordre" de Especes
     * @return array
     */
    public function getOrdre()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.ordre')
            ->where('s.nomVern != :notnull')
            ->setParameter('notnull', '')
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Mméthode pour obtenir famille de Especes
     * @return array
     */
    public function getFamille()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.famille')
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Méthode pour obtenir tous les oiseaux d'Especes
     * @return array
     */
    public function getBirds()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.nomVern', 's.id', 's.url')
            ->distinct('s.nomVern')
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @Méthode pour obtenir des oiseaux de l'espèce par familyparam $ family
     * @return array
     */
    public function getBirdsByFamily($family)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.nomVern', 's.id', 's.url')
            ->where('s.famille = :family')
            ->setParameter('family', $family)
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Méthode pour amener les familles d'Especes par ordre
     * @param $order
     * @return array
     */
    public function getFamilyByOrder($order)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.famille')
            ->where('s.ordre = :order')
            ->setParameter('order', $order)
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }
}
