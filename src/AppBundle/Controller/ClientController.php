<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Form\ClientType;
use AppBundle\Service\ClientServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends Controller
{

    private $clientService;

    public function __construct(ClientServiceInterface $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @Route("/clients", name="clients_list")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('client/list.html.twig', [
            'clients' => $this->clientService->listAll()
        ]);
    }

    /**
     * @Route("/clients/add", name="clients_add", methods={"GET","POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function addClient(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->clientService->register($client);
            return $this->redirectToRoute('clients_list');
        }

        return $this->render('client/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/clients/edit/{id}", name="clients_edit", methods={"GET","POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param Client $client
     * @return Response
     */

    public function editClient(Request $request, Client $client)
    {
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->clientService->update($client);
            return $this->redirectToRoute('clients_list');
        }

        return $this->render('client/edit.html.twig',[
            'client' => $client,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/clients/view/{id}", name="clients_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Client $client
     * @return Response
     */
    public function clientView(Client $client)
    {
        return $this->render('client/view.html.twig',[
            'client' => $client
        ]);
    }

}
