<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    public function getAllAds($page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('a')
        ->leftJoin('a.image', 'i')
        ->addSelect('i')
        ->leftJoin('a.categories', 'c')
        ->addSelect('c')
        ->orderBy('a.date', 'DESC')
        ->getQuery()
        ;

        $query
        // On définit l'annonce à partir de laquelle commencer la liste
        ->setFirstResult(($page-1) * $nbPerPage)
        // Ainsi que le nombre d'annonce à afficher sur une page
        ->setMaxResults($nbPerPage)
        ->execute();
    ;
    // Enfin, on retourne l'objet Paginator correspondant à la requête construite
    // (n'oubliez pas le use correspondant en début de fichier)
    return new Paginator($query, true);

    }

    public function getAdsByCategory($id)
    {
        $query = $this->createQueryBuilder('a')
        ->leftJoin('a.categories', 'c')
        ->addSelect('c')
        ->andwhere('c.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ;

        return $query->execute();
    }

    public function getAdsSearched($search)
    {
        $query = $this->_em->createQuery("SELECT a FROM App:Advert a WHERE a.title LIKE :search");
        $term = '%' . $search . '%';
        $query->setParameter('search', $term);
        return $query->getResult();
    }



}
