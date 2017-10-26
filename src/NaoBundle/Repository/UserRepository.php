<?php


namespace NaoBundle\Repository;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * UserRepository
 *
 * Cette classe a été générée par ORM de Doctrine. Ajoutez votre propre coutume
 * Méthodes de dépôt ci-dessous.
 */
class UserRepository extends EntityRepository

{
    /**
     * Nous utilisons cette méthode afin de trouver tous les utilisateurs Ornithologues qui ne sont pas encore accrédités pour valider les observations d'amateurs
     * @return array
     */
    public function getOrnithoNoValide()
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->where('u.enable = :enable')
            ->andWhere('u.roles = :roles')
            ->setParameters([
                'enable'=> false,
                'roles'=> 'a:1:{i:0;s:8:"ROLE_ORNITHOLOGUE";}'
            ]);

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getUsers($page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
        ;

        $query
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1)*$nbPerPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // On retourne l'objet Paginator correspondant à la requête construite
        return new Paginator($query, true);
    }

    public function getUsersParRole($page, $nbPerPage, $role)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%'.$role.'%')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
        ;

        $query
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1)*$nbPerPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // On retourne l'objet Paginator correspondant à la requête construite
        return new Paginator($query, true);
    }

    public function getAdministrateurs($page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :ADMINISTRATEUR')
            ->setParameter('Administrateur', '%ROLE_ADMINISTRATEUR%')
            ->orWhere('u.roles LIKE :ORNITHOLOGUE')
            ->setParameter('Ornithologue', '%ROLE_ORNITHOLOGUE')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
        ;

        $query
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1)*$nbPerPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // On retourne l'objet Paginator correspondant à la requête construite
        return new Paginator($query, true);
    }

    public function getUsersCount()
    {

            return $this->createQueryBuilder('u')
                ->select('count(u.id)')
                ->getQuery()
                ->getSingleScalarResult();

     }


    public function getUsersCountParRole($role)
    {

            return $this->createQueryBuilder('u')
                ->andWhere('u.roles LIKE :roles')
                ->setParameter('roles','%'.$role.'%')
                ->select('count(u.id)')
                ->getQuery()
                ->getSingleScalarResult();

    }


}