<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ReEntry;
use AppBundle\Form\ReEntryType;
use AppBundle\Service\ReturnServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReturnController extends Controller
{

    private $returnService;

    public function __construct(ReturnServiceInterface $returnService)
    {
        $this->returnService = $returnService;
    }

    /**
     * @Route("returns", name="returns_list")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function listReturns()
    {
        return $this->render('warehouse/return/list.html.twig', [
            'returns' => $this->returnService->listAll()
        ]);
    }

    /**
     * @Route("returns/add", name="returns_add")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @return Response
     */
    public function addReturn(Request $request)
    {

        $return = new ReEntry();
        $form = $this->createForm(ReEntryType::class, $return);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->returnService->add($return);
            return $this->redirectToRoute('returns_list');
        }

        return $this->render('warehouse/return/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("returns/edit/{id}", name="returns_edit")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editReturn(Request $request, int $id)
    {
        $return = $this->returnService->getById($id);
        $form = $this->createForm(ReEntryType::class, $return);
        $form->handleRequest($request);

        if (!$return) {
            return $this->redirectToRoute('returns_list');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->returnService->edit($return);
            return $this->redirectToRoute('returns_list');
        }

        return $this->render('warehouse/return/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("returns/delete/{id}", name="returns_delete")
     * @Security("has_role('ROLE_USER')")
     *
     * @param int $id
     * @return Response
     */
    public function deleteReturn(int $id)
    {
        $return = $this->returnService->getById($id);

        if ($return) {
            $this->returnService->delete($return);
        }

        return $this->redirectToRoute('returns_list');
    }

    /**
     * @Route("returns/view/{id}", name="returns_view")
     * @Security("has_role('ROLE_USER')")
     *
     * @param int $id
     * @return Response
     */
    public function viewReturn(int $id)
    {
        $return = $this->returnService->getById($id);

        if (!$return) {
            return $this->redirectToRoute('returns_list');
        }

        return $this->render('warehouse/return/view.html.twig',[
            'return' => $return
        ]);
    }
}
