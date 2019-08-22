<?php


namespace AppBundle\Repository;


interface ActivityRepositoryInterface
{
    /**
     * Returns clients joined with union of orders, reports and returns.
     *
     * @return array
     */
    public function getLogData(): array;
}