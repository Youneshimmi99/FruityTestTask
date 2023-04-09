<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function findAll(): array
    {
         return $this->createQueryBuilder('f')
            ->select('f.id, f.name, f.genus, f.family, f.order, f.favorite, n.carbohydrates, n.protein, n.fat, n.calories, n.sugar')
            ->leftJoin('f.nutrition', 'n')
            ->orderBy('f.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFavoriteFruits(): array
    {
        return $this->createQueryBuilder('f')
            ->select('f.id, f.name, f.genus, f.family, f.order, f.favorite, n.carbohydrates, n.protein, n.fat, n.calories, n.sugar')
            ->leftJoin('f.nutrition', 'n')
            ->where('f.favorite = :favorite')
            ->setParameter('favorite', true)
            ->orderBy('f.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findById(int $id): ?Fruit
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countFavorites(): int
    {
        return $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->andWhere('f.favorite = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function sumNutritionFavorite(): array
    {
        return $this->createQueryBuilder('f')
            ->select('SUM(n.carbohydrates) AS carbohydrates, SUM(n.protein) AS protein, SUM(n.fat) AS fat, SUM(n.calories) AS calories, SUM(n.sugar) AS sugar')
            ->leftJoin('f.nutrition', 'n')
            ->where('f.favorite = :favorite')
            ->setParameter('favorite', true)
            ->getQuery()
            ->getSingleResult();
    }
}