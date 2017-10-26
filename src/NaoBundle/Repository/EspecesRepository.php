<?php

namespace NaoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * EspecesRepository
 *
 * Cette classe a été générée par ORM de Doctrine. Ajoutez votre propre coutume
 * repository methods below.
 */
class EspecesRepository extends EntityRepository
{
    /**
     * @param string $Especes
     *
     * @return array
     */
    public function findLike($oiseau)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.nomVern LIKE :nomVern')
            ->setParameter('nomVern', "%$oiseau%")
            ->orderBy('a.nomVern')
            ->setMaxResults(20)
            ->getQuery()
            ->execute()
            ;
    }

    public function trouver($Id)
    {
        return $this
            ->createQueryBuilder('s')
            ->where('s.nomVern LIKE :nomVern')
            ->setParameter('nomVern', "%$Id%")
            ->distinct('s.nomVern')
            ->getQuery()
            ->execute();
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
    public function getOiseaux()
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
     * @Méthode pour obtenir des oiseaux de l'espèce par famille
     * @param $family
     * @return array
     */
    public function getOiseauxDeFamille($family)
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
    public function getFamilleByOrdre($order)
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

    /**
     * Méthode pour obtenir le nombre d'Especes
     *
     * @return array
     */
    public function getNbEspeces()
    {
        try {
            $query = $this->createQueryBuilder ('t')
                ->select ('COUNT(t.cdNom)')
                ->getQuery ()
                ->getSingleScalarResult ();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
        return $query;
    }

}
