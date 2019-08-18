<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\PrivatePerson;
use AppBundle\Form\PrivatePersonType;
use AppBundle\Service\ClientServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivatePersonController extends Controller
{

    private $clientService;

    public function __construct(ClientServiceInterface $clientService)
    {
        $this->clientService = $clientService;
    }

    public function listAll()
    {
        return $this->render('', []);
    }

    /**
     * @Route("/clients/private/add", name="private_person_add", methods={"GET","POST"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @return Response
     */
    public function addPrivatePerson(Request $request)
    {
        $privatePerson = new Client(true);
        $form = $this->createForm(PrivatePersonType::class, $privatePerson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->clientService->register($privatePerson);
            return $this->redirectToRoute('clients_list');
        }

        return $this->render('client/private/add.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/clients/private/edit/{id}", name="private_person_edit", methods={"GET","POST"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */

    public function editPrivatePerson(Request $request, $id)
    {
        $privatePerson = $this->clientService->getById($id);

        if (!$privatePerson) {
            return $this->redirectToRoute('clients_list');
        }

        $form = $this->createForm(PrivatePersonType::class,$privatePerson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->clientService->update($privatePerson);
            return $this->redirectToRoute('clients_list');
        }

        return $this->render('client/private/edit.html.twig',[
            'privatePerson' => $privatePerson,
            'form' => $form->createView()
        ]);

    }
}
