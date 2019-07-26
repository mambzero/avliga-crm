<?php


namespace AppBundle\Service;


use AppBundle\Entity\Client;

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
     * @return Client[]
     */
    public function listAll(): array;

    /**
     * @param int $id
     * @return Client|null
     */
    public function getById(int $id): ?Client;
}