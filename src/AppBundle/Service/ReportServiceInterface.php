<?php


namespace AppBundle\Service;


use AppBundle\Entity\Report;

interface ReportServiceInterface
{
    /**
     * @param Report $report
     * @return bool
     */
    public function save(Report $report): bool;

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
    public function getById(int $id): ?Report;

    /**
     * @param int|null $id
     * @return Report|null
     */
    public function getInstance(int $id = null): ?Report;

    /**
     * @return array
     */
    public function getEarningsByMonths(): array;

    /**
     * @return float
     */
    public function getEarningsForCurrentYear(): float;

    /**
     * @return float
     */
    public function getEarningsForCurrentMonth(): float;

    /**
     * @return int
     */
    public function reportsThisMonth(): int;

}