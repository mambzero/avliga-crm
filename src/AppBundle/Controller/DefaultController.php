<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderServiceInterface;
use AppBundle\Service\ReportServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    private $reportService;
    private $orderService;

    public function __construct(ReportServiceInterface $reportService, OrderServiceInterface $orderService)
    {
        $this->reportService = $reportService;
        $this->orderService = $orderService;
    }

    /**
     * @Route("/", name="dashboard")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $earningsForCurrentMonth = $this->reportService->getEarningsForCurrentMonth();
        $earningsForCurrentYear = $this->reportService->getEarningsForCurrentYear();
        $ordersThisMonth = $this->orderService->ordersThisMonth();
        $reportsThisMonth = $this->reportService->reportsThisMonth();

        return $this->render('default/dashboard.html.twig',[
            'thisMonth' => $earningsForCurrentMonth,
            'thisYear' => $earningsForCurrentYear,
            'orders' => $ordersThisMonth,
            'reports' => $reportsThisMonth
        ]);
    }

    /**
     * @Route("/earnings", name="earnings", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function getEarnings()
    {
        $earnings = $this->reportService->getEarningsByMonths();
        return new JsonResponse($earnings,Response::HTTP_OK);
    }
}
