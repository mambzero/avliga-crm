<?php


namespace AppBundle\Service;


use AppBundle\Entity\ReEntry;

interface ReturnServiceInterface
{
    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function add(ReEntry $reEntry): bool;

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function edit(ReEntry $reEntry): bool;

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
    public function getById(int $id): ?ReEntry;
}