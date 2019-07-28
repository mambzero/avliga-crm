<?php


namespace AppBundle\Service;


interface WarehouseServiceInterface
{
    /**
     * @return array
     */
    public function listStocks(): array;

    /**
     * @param int $id
     * @return int
     */
    public function getProductStock(int $id): int;
}