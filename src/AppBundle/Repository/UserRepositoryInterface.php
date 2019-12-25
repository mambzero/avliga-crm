<?php


namespace AppBundle\Repository;


use AppBundle\Entity\User;

interface UserRepositoryInterface
{

    /**
     * @return User[]
     */
    public function listAll(): array;

    /**
     * @param User $user
     * @return bool
     */
    public function insert(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool;

    /**
     * @param int $id
     * @return User|null
     */
    public function findOne(int $id): ?User;

    /**
     * @param string $email
     * @return User|Object|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * @param int $id
     * @return string
     */
    public function getEmailById(int $id): ?string;

    /**
     * @param int $id
     * @return string
     */
    public function getPassword(int $id): ?string;

}