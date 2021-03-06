<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return mixed
     */
    public function findAvailableCategoriesForFilter()
    {
        return $this->createQueryBuilder('category')
            ->select('category')
            ->innerJoin('category.adverts', 'adverts')
            ->where('adverts.acceptedOffer IS NULL')
            ->andWhere('adverts.isConfirmed = 1')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findUsersTopCategoryTitles(User $user)
    {
        $entityManager = $this->getEntityManager();

        return $query = $entityManager->createQuery(
            'SELECT c, COUNT(1) AS HIDDEN Count
       FROM App\Entity\Category c
       JOIN c.adverts a
       JOIN a.offers o
       JOIN o.user u
       WHERE u.id = ?1 AND a.isDeleted = 0 AND a.isConfirmed = 1
       GROUP BY c.id
       ORDER BY Count DESC'
        )->setParameter(1, $user->getId())
            ->setMaxResults("3")
            ->getResult();
    }
}
