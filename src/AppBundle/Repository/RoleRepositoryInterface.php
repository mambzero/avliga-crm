<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Role;

interface RoleRepositoryInterface
{
    /**
     * @return Role[]
     */
    public function getRoles(): array;

    /**
     * @param string $name
     * @return Role|Object|null
     */
    public function findByName(string $name): ?Role;

    /**
     * @param Role $role
     * @return bool
     */
    public function insert(Role $role): bool;

    /**
     * @param Role $role
     * @return bool
     */
    public function update(Role $role): bool;

    /**
     * @param Role $role
     * @return bool
     */
    public function delete(Role $role): bool;

    /**
     * @param int $id
     * @return Role|Object|null
     */
    public function findOne(int $id): ?Role;

    /**
     * @param int $id
     * @return string|null
     */
    public function findNameById(int $id): ?string;
}