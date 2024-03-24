<?php

namespace App\Repository;

use App\Entity\UrlCode;
use App\Exceptions\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrlCode>
 *
 * @method UrlCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlCode[]    findAll()
 * @method UrlCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlCode::class);
    }

    public function fetchAvailableKey(): string
    {
        $connection = $this->getEntityManager()->getConnection();
        $code = $connection->prepare(
                'UPDATE url_code
                    SET is_used=1
                    WHERE id IN (select id from url_code where is_used=0 limit 1)
                    RETURNING code'
            )
            ->executeQuery()
            ->fetchOne();

        if (!$code) {
            throw new EntityNotFoundException('No available keys');
        }

        return $code;
    }

    public function saveKey(UrlCode $keyRecord): void
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'INSERT INTO "url_code" (code, is_used) VALUES (:code, :is_used)';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("code", $keyRecord->getCode());
        $stmt->bindValue("is_used", (int)$keyRecord->isIsUsed());
        $stmt->executeQuery();
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
