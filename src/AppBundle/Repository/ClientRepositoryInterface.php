<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Client;

interface ClientRepositoryInterface
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
     * @return Client[]
     */
    public function listAll(): array;

    /**
     * @param int $id
     * @return Client|null
     */
    public function findOne(int $id): ?Client;

}