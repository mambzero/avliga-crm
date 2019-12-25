<?php


namespace AppBundle\Repository;


use AppBundle\Entity\ReportDetail;

interface ReportDetailRepositoryInterface
{
    /**
     * @param int $id
     * @return int
     */
    public function getQuantity(int $id): int;

    /**
     * @param ReportDetail $reportDetail
     * @return bool
     */
    public function remove(ReportDetail $reportDetail): bool;
}