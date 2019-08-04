<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Report;
use AppBundle\Entity\ReportDetail;
use AppBundle\Form\ReportType;
use AppBundle\Service\ProductServiceInterface;
use AppBundle\Service\ReportServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends Controller
{

    private $reportService;
    private $productService;

    public function __construct(ReportServiceInterface $reportService, ProductServiceInterface $productService)
    {
        $this->reportService = $reportService;
        $this->productService = $productService;
    }

    /**
     * @Route("/reports", name="reports_list")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function listReport()
    {
        return $this->render('report/list.html.twig',[
            'reports' => $this->reportService->listAll()
        ]);
    }

    /**
     * @Route("/reports/create", name="reports_create")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @return Response
     */
    public function createReport(Request $request)
    {
        $report = $this->reportService->getInstance();

        $form = $this->createForm(ReportType::class,$report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reportService->save($report);
            return $this->redirectToRoute('reports_list');
        }

        return $this->render('report/create.html.twig',[
            'form' => $form->createView(),
            'products' => $this->productService->getProductNames()
        ]);
    }

    /**
     * @Route("/reports/edit/{id}", name="reports_edit")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editReport(Request $request, $id)
    {
        $report = $this->reportService->getInstance($id);

        if (!$report) {
            return $this->redirectToRoute('reports_list');
        }

        $form = $this->createForm(ReportType::class,$report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reportService->save($report);
            return $this->redirectToRoute('reports_list');
        }

        return $this->render('report/edit.html.twig',[
            'form' => $form->createView(),
            'products' => $this->productService->getProductNames()
        ]);
    }

    /**
     * @Route("/reports/delete/{id}", name="reports_delete")
     * @Security("has_role('ROLE_USER')")
     *
     * @param $id
     * @return RedirectResponse
     */

    public function deleteOrder($id)
    {
        $report = $this->reportService->getById($id);

        if ($report) {
            $this->reportService->delete($report);
        }

        return $this->redirectToRoute('reports_list');
    }

    /**
     * @Route("/reports/view/{id}", name="reports_view")
     * @Security("has_role('ROLE_USER')")
     *
     * @param $id
     * @return Response
     */
    public function viewOrder($id)
    {
        $report = $this->reportService->getById($id);

        if (!$report) {
            return $this->redirectToRoute('orders_list');
        }

        return $this->render('report/view.html.twig',[
            'report' => $report
        ]);
    }
}
