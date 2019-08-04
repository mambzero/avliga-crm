<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Report;

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
}