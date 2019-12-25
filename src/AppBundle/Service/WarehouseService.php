<?php


namespace AppBundle\Service;

use AppBundle\Repository\WarehouseRepositoryInterface;

class WarehouseService implements WarehouseServiceInterface
{

    private $warehouseRepository;

    public function __construct(WarehouseRepositoryInterface $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * @return array
     */
    public function listStocks(): array
    {
        return $this->warehouseRepository->getStocks();
    }

    /**
     * @param int $id
     * @return int
     */
    public function getProductStock(int $id): int
    {
        return $this->warehouseRepository->getStock($id);
    }
}