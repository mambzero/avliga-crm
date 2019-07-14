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
     * @return Client[]
     */
    public function listAll();
}