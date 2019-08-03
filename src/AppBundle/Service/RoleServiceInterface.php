<?php


namespace AppBundle\Service;


use AppBundle\Entity\Role;

interface RoleServiceInterface
{
    /**
     * @return Role[]
     */
    public function listRoles(): array;

    /**
     * @param Role $role
     * @return bool
     */
    public function add(Role $role): bool;

    /**
     * @param Role $role
     * @return bool
     */
    public function edit(Role $role): bool;

    /**
     * @param Role $role
     * @return bool
     */
    public function remove(Role $role): bool;

    /**
     * @param int $id
     * @return Role|Object|null
     */
    public function getById(int $id): ?Role;
}