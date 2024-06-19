<?php

namespace App\Repository;

use App\Dto\LogLineDto;
use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 */
class LogRepository extends ServiceEntityRepository implements LogRecordsInterface
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Log::class);
        $this->entityManager = $entityManager;
    }

    public function getLogRecordsCount(array $filters = []): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('COUNT(l.id)');

        if (empty($filters)) {
            return (int) $qb->getQuery()->getSingleScalarResult();
        }

        if (! empty($filters['serviceNames'])) {
            $qb->andWhere('l.serviceName IN (:serviceNames)')
                ->setParameter('serviceNames', $filters['serviceNames']);
        }

        if (isset($filters['statusCode']) && $filters['statusCode'] !== '') {
            $qb->andWhere('l.statusCode = :statusCode')
                ->setParameter('statusCode', $filters['statusCode']);
        }

        if (isset($filters['startDate']) && $filters['startDate'] !== '') {
            $qb->andWhere('l.date >= :startDate')
                ->setParameter('startDate', $filters['startDate']);
        }

        if (isset($filters['endDate']) && $filters['endDate'] !== '') {
            $qb->andWhere('l.date <= :endDate')
                ->setParameter('endDate', $filters['endDate']);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function addLogRecord(LogLineDto $logLineDto): bool
    {
        try {
            $log = new Log();
            $log->setServiceName($logLineDto->getServiceName());
            $log->setStatusCode($logLineDto->getStatusCode());
            $log->setDate($logLineDto->getDate());

            // Persist the entity
            $this->entityManager->persist($log);

            // Flush the entity manager to write the changes to the database
            $this->entityManager->flush();

            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
