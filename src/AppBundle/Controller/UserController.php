<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/users", name="users_list")
     * @Security("has_role('ROLE_ADMIN')", message="No Access!")
     *
     * @return Response
     */
    public function listUsers()
    {
        return $this->render('user/list.html.twig', [
            'users' => $this->userService->listAll()
        ]);
    }

    /**
     * @Route("/users/add", name="users_add")
     * @Security("has_role('ROLE_ADMIN')", message="No Access!")
     *
     * @param Request $request
     * @return Response
     */
    public function addUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->add($user);
            return $this->redirectToRoute('users_list');
        }

        return $this->render('user/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/edit/{id}", name="users_edit")
     * @Security("has_role('ROLE_ADMIN')", message="No Access!")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editUser(Request $request, $id)
    {
        $user = $this->userService->getById($id);
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->edit($user);
            return $this->redirectToRoute('users_list');
        }

        return $this->render('user/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/view/{id}", name="users_view")
     * @Security("has_role('ROLE_ADMIN')", message="No Access!")
     *
     * @param $id
     * @return Response
     */
    public function viewUser($id)
    {
        $user = $this->userService->getById($id);

        if (!$user) {
            return $this->redirectToRoute('users_list');
        }

        return $this->render('user/view.html.twig',[
            'user' => $user
        ]);
    }

    /**
     * @Route("/users/delete/{id}", name="users_delete")
     * @Security("has_role('ROLE_ADMIN')", message="No Access!")
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteUser($id)
    {
        $user = $this->userService->getById($id);

        if ($user) {
            $this->userService->remove($user);
        }

        return $this->redirectToRoute('users_list');

    }
}
