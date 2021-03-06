<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use AppBundle\Form\EntryType;
use AppBundle\Service\EntryServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EntryController extends Controller
{

    private $entryService;

    public function __construct(EntryServiceInterface $entryService)
    {
        $this->entryService = $entryService;
    }

    /**
     * @Route("/entries", name="entries_list")
     * @Security("has_role('ROLE_USER')")
     */
    public function listEntries()
    {
        return $this->render('warehouse/entry/list.html.twig', array(
            'entries' => $this->entryService->listAll()
        ));
    }

    /**
     * @Route("/entries/add", name="entries_add")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @return Response
     */
    public function addEntry(Request $request)
    {
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->entryService->add($entry)) {
                return $this->redirectToRoute('entries_list');
            }
            return new Response('error');
        }

        return $this->render('warehouse/entry/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/entries/edit/{id}", name="entries_edit")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editEntry(Request $request, $id)
    {
        $entry = $this->entryService->getById($id);

        if (!$entry) {
            return $this->redirectToRoute('entries_list');
        }

        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->entryService->edit($entry)) {
                return $this->redirectToRoute('entries_list');
            }
            return new Response('error');
        }

        return $this->render('warehouse/entry/edit.html.twig', array(
            'entry' => $entry,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/entries/view/{id}", name="entries_view")
     * @Security("has_role('ROLE_USER')")
     *
     * @param $id
     * @return Response
     */
    public function viewEntry($id)
    {
        $entry = $this->entryService->getById($id);

        if (!$entry) {
            return $this->redirectToRoute('entries_list');
        }

        return $this->render('warehouse/entry/view.html.twig', array(
            'entry' => $entry
        ));
    }

    /**
     * @Route("/entries/delete/{id}", name="entries_delete")
     * @Security("has_role('ROLE_USER')")
     *
     * @param $id
     * @return Response
     */
    public function deleteEntry($id)
    {

        $entry = $this->entryService->getById($id);

        if ($entry) {
            $this->entryService->remove($entry);
        }

        return $this->redirectToRoute('entries_list');
    }
}
