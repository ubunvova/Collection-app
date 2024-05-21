<?php

namespace App\Repository;

use App\Entity\ItemCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemCollection>
 */
class ItemCollectionRepository extends ServiceEntityRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                ManagerRegistry                         $registry,

    )
    {
        parent::__construct($registry, ItemCollection::class);
    }

    public function getItemCollectionWithCustomAttributes(int $collectionId)
    {
        $query = $this->entityManager->createQuery('
        SELECT c, ca
        FROM App\Entity\ItemCollection c
        LEFT JOIN c.customItemAttributes ca
        WHERE c.id = :collectionId
    ')->setParameter('collectionId', $collectionId);

        return $query->getOneOrNullResult();
    }

    public function getCategoryWithItemCollection($collectionId)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.category', 'c')
            ->addSelect('c')
            ->where('i.id = :collectionId')
            ->setParameter('collectionId', $collectionId)
            ->getQuery()
            ->getResult();

    }
}
