<?php


namespace AppBundle\Service;


use AppBundle\Entity\ReEntry;
use AppBundle\Repository\ReturnRepositoryInterface;

class ReturnService implements ReturnServiceInterface
{

    private $returnRepository;

    public function __construct(ReturnRepositoryInterface $returnRepository)
    {
        $this->returnRepository = $returnRepository;
    }

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function add(ReEntry $reEntry): bool
    {
        return $this->returnRepository->insert($reEntry);
    }

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function edit(ReEntry $reEntry): bool
    {
        return $this->returnRepository->update($reEntry);
    }

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function delete(ReEntry $reEntry): bool
    {
        return $this->returnRepository->delete($reEntry);
    }

    /**
     * @return ReEntry[]
     */
    public function listAll(): array
    {
        return $this->returnRepository->listAll();
    }

    /**
     * @param int $id
     * @return ReEntry|Object|null
     */
    public function getById(int $id): ?ReEntry
    {
        return $this->returnRepository->findOne($id);
    }
}