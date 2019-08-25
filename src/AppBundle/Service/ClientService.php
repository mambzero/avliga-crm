<?php


namespace AppBundle\Service;


use AppBundle\Entity\Client;
use AppBundle\Entity\Product;
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
     * @param bool $active
     * @return Client[]
     */
    public function listAll($active = false): array
    {
        if ($active === true) {
            return $this->clientRepository->listActive();
        }
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

    /**
     * @param Client $client
     * @return array
     */
    public function getClientStocks(Client $client): array
    {
        $clientInfo = $this->clientRepository->getClientInfo($client);

        $stocks = array_map(function ($item){
            $item['reported'] = $item['reported'] === null ? 0 : $item['reported'];
            $item['returned'] = $item['returned'] === null ? 0 : $item['returned'];
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'stock' => $item['ordered'] - $item['reported'] - $item['returned']
            ];
        }, $clientInfo);

        $stocks = array_filter($stocks, function ($item) {
            return $item['stock'] > 0;
        });

        usort($stocks, function ($stock1, $stock2) {
            return $stock1['title'] <=> $stock2['title'];
        });

        return $stocks;

    }

    /**
     * @param Client $client
     * @param Product $product
     * @return int|null
     */
    public function getClientStockByProduct(Client $client, Product $product): ?int
    {

        if ($product->isEBook()) {
            return PHP_INT_MAX;
        }

        $clientInfo = $this->getClientStocks($client);

        foreach ($clientInfo as $item) {
            if ($item['id'] == $product->getId()) {
                return $item['stock'];
            }
        }

        return null;
    }
}