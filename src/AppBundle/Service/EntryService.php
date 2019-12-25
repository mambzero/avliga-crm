<?php


namespace AppBundle\Service;


use AppBundle\Entity\Entry;
use AppBundle\Repository\EntryRepositoryInterface;

class EntryService implements EntryServiceInterface
{
    /**
     * @var EntryRepositoryInterface
     */
    private $entryRepository;

    /**
     * EntryService constructor.
     * @param EntryRepositoryInterface $entryRepository\
     */
    public function __construct(EntryRepositoryInterface $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    /**
     * @param Entry $entry
     * @return bool
     */
    public function add(Entry $entry): bool
    {
        return $this->entryRepository->insert($entry);
    }

    /**
     * @param Entry $entry
     * @return bool
     */
    public function edit(Entry $entry): bool
    {
        return $this->entryRepository->update($entry);
    }

    /**
     * @return array
     */
    public function listAll(): array
    {
        return $this->entryRepository->listAll();
    }

    /**
     * @param Entry $entry
     * @return bool
     */
    public function remove(Entry $entry): bool
    {
        return $this->entryRepository->delete($entry);
    }

    /**
     * @param int $id
     * @return Entry|Object|null
     */
    public function getById(int $id): ?Entry
    {
        return $this->entryRepository->findOne($id);
    }
}