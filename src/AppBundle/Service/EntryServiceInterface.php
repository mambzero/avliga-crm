<?php


namespace AppBundle\Service;


use AppBundle\Entity\Entry;

interface EntryServiceInterface
{
    /**
     * @param Entry $entry
     * @return bool
     */
    public function add(Entry $entry): bool;

    /**
     * @param Entry $entry
     * @return bool
     */
    public function edit(Entry $entry): bool;

    /**
     * @return array
     */
    public function listAll(): array;

    /**
     * @param Entry $entry
     * @return bool
     */
    public function remove(Entry $entry): bool;
}