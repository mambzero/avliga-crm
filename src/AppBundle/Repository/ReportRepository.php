<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Report;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

/**
 * ReportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReportRepository extends EntityRepository implements ReportRepositoryInterface
{
    /**
     * ReportRepository constructor.
     * @param EntityManagerInterface|EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new Mapping\ClassMetadata(Report::class));
    }

    /**
     * @param Report $report
     * @return bool
     */
    public function insert(Report $report): bool
    {
        try {
            $this->_em->persist($report);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param Report $report
     * @return bool
     */
    public function update(Report $report): bool
    {
        try {
            $this->_em->merge($report);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param Report $report
     * @return bool
     */
    public function delete(Report $report): bool
    {
        try {
            $this->_em->remove($report);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return Report[]
     */
    public function listAll(): array
    {
        return $this->createQueryBuilder('r')
            ->select([
                'r.id',
                'c.company as client',
                'ROUND(sum(d.price * d.quantity) - sum(((d.price * d.quantity)*d.discount)/100),2) as total',
                'DATE_FORMAT(r.dateAdded, \'%Y-%m-%d %H:%i\') as date'
            ])
            ->join('r.client','c')
            ->leftJoin('r.details','d')
            ->groupBy('r.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return Report|Object|null
     */
    public function findOne(int $id): ?Report
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param DateTime $datetime
     * @return array
     */
    public function getEarningsByMonths(Datetime $datetime): array
    {
        return $this->createQueryBuilder('r')
            ->select([
                'ROUND(SUM((d.quantity * d.price) * ((100 - d.discount)/100)), 2) as total',
                'DATE_FORMAT(r.dateAdded, \'%b\') as month',
                'DATE_FORMAT(r.dateAdded, \'%Y\') as year',
                'DATE_FORMAT(r.dateAdded, \'%c\') as c',
            ])
            ->leftJoin('r.details', 'd')
            ->orderBy('year', 'DESC')
            ->orderBy('c', 'ASC')
            ->groupBy('month, year')
            ->having('year = :year')
            ->setParameter('year',$datetime->format('Y'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Datetime $datetime
     * @return float|null
     * @throws NonUniqueResultException
     */
    public function getEarningsAnnual(Datetime $datetime): ?float
    {
        $result = $this->createQueryBuilder('r')
            ->select([
                'ROUND(SUM((d.quantity * d.price) * ((100 - d.discount)/100)), 2) as total',
                'DATE_FORMAT(r.dateAdded, \'%Y\') as year',
            ])
            ->leftJoin('r.details', 'd')
            ->groupBy('year')
            ->having('year = :year')
            ->setParameter('year',$datetime->format('Y'))
            ->getQuery()
            ->getOneOrNullResult();

        return $result['total'];
    }

    /**
     * @param Datetime $datetime
     * @return float|null
     * @throws NonUniqueResultException
     */
    public function getEarningsMonthly(Datetime $datetime): ?float
    {
        $result = $this->createQueryBuilder('r')
            ->select([
                'ROUND(SUM((d.quantity * d.price) * ((100 - d.discount)/100)), 2) as total',
                'DATE_FORMAT(r.dateAdded, \'%b\') as month',
                'DATE_FORMAT(r.dateAdded, \'%Y\') as year',
            ])
            ->leftJoin('r.details', 'd')
            ->groupBy('month, year')
            ->having('month = :month AND year = :year')
            ->setParameters([
                'month' => $datetime->format('M'),
                'year' => $datetime->format('Y')
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return $result['total'];
    }

    /**
     * @return int|null
     * @throws NonUniqueResultException
     */
    public function getReportedProductsCount(): ?int
    {
        $result =  $this->createQueryBuilder('r')
            ->select('SUM(d.quantity) as quantity')
            ->leftJoin('r.details', 'd')
            ->getQuery()
            ->getOneOrNullResult();

        return $result['quantity'];
    }
}
