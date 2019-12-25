<?php


namespace AppBundle\Service;


use AppBundle\Entity\Role;
use AppBundle\Repository\RoleRepositoryInterface;

class RoleService implements RoleServiceInterface
{

    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return Role[]
     */
    public function listRoles(): array
    {
        return $this->roleRepository->getRoles();
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function add(Role $role): bool
    {
        return $this->roleRepository->insert($role);
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function edit(Role $role): bool
    {
        $nameInDB = $this->roleRepository->findNameById($role->getId());

        if ($nameInDB === 'ROLE_ADMIN' || $nameInDB === 'ROLE_USER') {
            return false;
        }

        return $this->roleRepository->update($role);
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function remove(Role $role): bool
    {
        if ($role->isAdminRole() || $role->isUserRole()) {
            return false;
        }
        return $this->roleRepository->delete($role);
    }

    /**
     * @param int $id
     * @return Role|Object|null
     */
    public function getById(int $id): ?Role
    {
        return $this->roleRepository->findOne($id);
    }
}