<?php


namespace AppBundle\Service;


use AppBundle\Entity\Client;
use AppBundle\Entity\Product;

interface ClientServiceInterface
{
    /**
     * @param Client $client
     * @return bool
     */
    public function register(Client $client): bool;

    /**
     * @param Client $client
     * @return bool
     */
    public function update(Client $client): bool;

    /**
     * @param bool $active
     * @return Client[]
     */
    public function listAll($active = false): array;

    /**
     * @param int $id
     * @return Client|null
     */
    public function getById(int $id): ?Client;

    /**
     * @param Client $client
     * @return array
     */
    public function getClientStocks(Client $client): array;

    /**
     * @param Client $client
     * @param Product $product
     * @return int|null
     */
    public function getClientStockByProduct(Client $client, Product $product): ?int;
}