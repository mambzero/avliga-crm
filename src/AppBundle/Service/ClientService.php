<?php


namespace AppBundle\Service;


use AppBundle\Entity\Client;
use AppBundle\Repository\ClientRepositoryInterface;

class ClientService implements ClientServiceInterface
{
    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * ClientService constructor.
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Client $client
     * @return bool
     */
    public function register(Client $client)
    {
        return $this->clientRepository->register($client);
    }

    /**
     * @param Client $client
     * @return bool
     */
    public function update(Client $client)
    {
        return $this->clientRepository->update($client);
    }

    /**
     * @return Client[]
     */
    public function listAll()
    {
        return $this->clientRepository->listAll();
    }
}