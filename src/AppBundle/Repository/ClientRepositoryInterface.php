<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Client;

interface ClientRepositoryInterface
{
    /**
     * @param Client $client
     * @return bool
     */
    public function register(Client $client);

}