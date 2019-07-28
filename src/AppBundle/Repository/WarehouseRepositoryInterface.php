<?php


namespace AppBundle\Repository;


interface WarehouseRepositoryInterface
{
    /**
     * @return array
     */
    public function getStocks(): array;

    /**
     * @param int $id
     * @return int
     */
    public function getStock(int $id): int;
}