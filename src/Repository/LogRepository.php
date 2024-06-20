<?php

namespace App\Repository;

use App\Dto\LogFilterDto;
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
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Log::class);
        $this->entityManager = $entityManager;
    }

    public function getLogRecordsCount(LogFilterDto $logFilters): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('COUNT(l.id)');

        if (! empty($logFilters->serviceNames)) {
            $qb->andWhere('l.serviceName IN (:serviceNames)')
                ->setParameter('serviceNames', $logFilters->serviceNames);
        }

        if (isset($logFilters->statusCode)) {
            $qb->andWhere('l.statusCode = :statusCode')
                ->setParameter('statusCode', $logFilters->statusCode);
        }

        if (isset($logFilters->startDate)) {
            $qb->andWhere('l.date >= :startDate')
                ->setParameter('startDate', $logFilters->startDate);
        }

        if (isset($logFilters->endDate)) {
            $qb->andWhere('l.date <= :endDate')
                ->setParameter('endDate', $logFilters->endDate);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function addLogRecord(LogLineDto $logLineDto): void
    {
        $this->entityManager
            ->persist(
                Log::hydrate($logLineDto),
            );
        $this->entityManager->flush();
    }
}
