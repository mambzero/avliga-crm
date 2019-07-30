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
}