<?php


namespace AppBundle\Repository;


use AppBundle\Entity\ReEntry;

interface ReturnRepositoryInterface
{
    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function insert(ReEntry $reEntry): bool;

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function update(ReEntry $reEntry): bool;

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function delete(ReEntry $reEntry): bool;

    /**
     * @return ReEntry[]
     */
    public function listAll(): array;

    /**
     * @param int $id
     * @return ReEntry|Object|null
     */
    public function findOne(int $id): ?ReEntry;

    /**
     * @param int $id
     * @return int
     */
    public function getQuantity(int $id): int;
}