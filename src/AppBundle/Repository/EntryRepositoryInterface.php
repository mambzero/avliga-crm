<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Entry;

interface EntryRepositoryInterface
{
    /**
     * @param Entry $entry
     * @return bool
     */
    public function insert(Entry $entry): bool;

    /**
     * @param Entry $entry
     * @return bool
     */
    public function update(Entry $entry): bool;

    /**
     * @return Entry[]
     */
    public function listAll(): array;

    /**
     * @param Entry $entry
     * @return bool
     */
    public function delete(Entry $entry): bool;

    /**
     * @param int $id
     * @return Entry|Object|null
     */
    public function findOne(int $id): ?Entry;
}