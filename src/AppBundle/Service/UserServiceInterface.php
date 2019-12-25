<?php


namespace AppBundle\Service;


use AppBundle\Entity\User;

interface UserServiceInterface
{
    /**
     * @param User $user
     * @return bool
     */
    public function add(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function remove(User $user): bool;

    /**
     * @param int $id
     * @return User|Object|null
     */
    public function getById(int $id): ?User;

    /**
     * @return User[]
     */
    public function listAll(): array;
}