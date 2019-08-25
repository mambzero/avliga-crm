<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Report;
use Datetime;

interface ReportRepositoryInterface
{
    /**
     * @param Report $report
     * @return bool
     */
    public function insert(Report $report): bool;

    /**
     * @param Report $report
     * @return bool
     */
    public function update(Report $report): bool;

    /**
     * @param Report $report
     * @return bool
     */
    public function delete(Report $report): bool;

    /**
     * @return Report[]
     */
    public function listAll(): array;

    /**
     * @param int $id
     * @return Report|Object|null
     */
    public function findOne(int $id): ?Report;

    /**
     * @param Datetime $datetime
     * @return array
     */
    public function getEarningsByMonths(Datetime $datetime): array;

    /**
     * @param Datetime $datetime
     * @return float|null
     */
    public function getEarningsAnnual(Datetime $datetime): ?float;

    /**
     * @param Datetime $datetime
     * @return float|null
     */
    public function getEarningsMonthly(DateTime $datetime): ?float;

    /**
     * @param Datetime $datetime
     * @return int|null
     */
    public function countReportsByMonth(Datetime $datetime): ?int;
}