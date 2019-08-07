<?php


namespace AppBundle\Repository;


interface ReportDetailRepositoryInterface
{
    /**
     * @param int $id
     * @return int
     */
    public function getQuantity(int $id): int;
}