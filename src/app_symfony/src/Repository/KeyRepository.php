<?php

namespace App\Repository;

use App\Entity\Key;
use App\Exceptions\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Key>
 *
 * @method Key|null find($id, $lockMode = null, $lockVersion = null)
 * @method Key|null findOneBy(array $criteria, array $orderBy = null)
 * @method Key[]    findAll()
 * @method Key[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Key::class);
    }

    public function fetchAvailableKey(): string
    {
        /** @var Key $keyRecord */
        $keyRecord = $this->createQueryBuilder('k')
            ->andWhere('k.isUsed = 0')
            ->getQuery()
            ->getOneOrNullResult();
        if (!$keyRecord) {
            throw new EntityNotFoundException('No available keys');
        }
        $keyRecord->setIsUsed(1);
        $this->getEntityManager()->persist($keyRecord);
        $this->getEntityManager()->flush();

        return $keyRecord->getCode();
    }

//    /**
//     * @return Key[] Returns an array of Key objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Key
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
