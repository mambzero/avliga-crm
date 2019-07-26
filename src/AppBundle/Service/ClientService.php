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
    public function register(Client $client): bool
    {
        return $this->clientRepository->register($client);
    }

    /**
     * @param Client $client
     * @return bool
     */
    public function update(Client $client): bool
    {
        return $this->clientRepository->update($client);
    }

    /**
     * @return Client[]
     */
    public function listAll(): array
    {
        return $this->clientRepository->listAll();
    }

    /**
     * @param int $id
     * @return Client|null
     */
    public function getById(int $id): ?Client
    {
        return $this->clientRepository->findOne($id);
    }
}