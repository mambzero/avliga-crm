<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Form\RoleType;
use AppBundle\Service\RoleServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends Controller
{

    private $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @Route("/roles", name="roles_list")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function listRoles()
    {
        return $this->render('role/list.html.twig',[
            'roles' => $this->roleService->listRoles()
        ]);
    }

    /**
     * @Route("/roles/add", name="roles_add")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return Response
     */
    public function addRole(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class,$role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roleService->add($role);
            return $this->redirectToRoute('roles_list');
        }

        return $this->render('role/add.html.twig',[
           'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/roles/edit/{id}", name="roles_edit")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editRole(Request $request, $id)
    {
        $role = $this->roleService->getById($id);

        if (!$role) {
            return $this->redirectToRoute('roles_list');
        }

        $form = $this->createForm(RoleType::class,$role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roleService->edit($role);
            return $this->redirectToRoute('roles_list');
        }

        return $this->render('role/edit.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/roles/delete/{id}", name="roles_delete")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param $id
     * @return RedirectResponse
     */

    public function deleteRole($id)
    {
        $role = $this->roleService->getById($id);

        if ($role) {
            $this->roleService->remove($role);
        }

        return $this->redirectToRoute('roles_list');
    }

    /**
     * @Route("/roles/view/{id}", name="roles_view")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param int $id
     * @return Response
     */
    public function viewRole(int $id)
    {

        $role = $this->roleService->getById($id);

        if (!$role) {
            return $this->redirectToRoute('roles_list');
        }

        return $this->render('role/view.html.twig',[
            'role' => $role
        ]);
    }
}
