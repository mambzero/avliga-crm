<?php


namespace AppBundle\Service;


use AppBundle\Entity\Client;

interface ClientServiceInterface
{
    /**
     * @param Client $client
     * @return bool
     */
    public function register(Client $client);

    /**
     * @param Client $client
     * @return bool
     */
    public function update(Client $client);

    /**
     * @return Client[]
     */
    public function listAll();
}