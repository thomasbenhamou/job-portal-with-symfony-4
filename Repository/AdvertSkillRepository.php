<?php

namespace App\Repository;

use App\Entity\AdvertSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdvertSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvertSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvertSkill[]    findAll()
 * @method AdvertSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertSkillRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdvertSkill::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
