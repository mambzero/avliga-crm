<?php

namespace AppBundle\Controller;

use AppBundle\Service\DashboardServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * @Route("/", name="dashboard")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $earningsForCurrentMonth = $this->dashboardService->getEarningsForCurrentMonth();
        $earningsForCurrentYear = $this->dashboardService->getEarningsForCurrentYear();
        $ordersThisMonth = $this->dashboardService->ordersThisMonth();
        $reportsThisMonth = $this->dashboardService->reportsThisMonth();

        $ordersCompleted = $this->dashboardService->getOrdersCompletedPercentage();

        $years = $this->dashboardService->getChartAreaReportYears();

        return $this->render('default/dashboard.html.twig',[
            'thisMonth' => $earningsForCurrentMonth,
            'thisYear' => $earningsForCurrentYear,
            'orders' => $ordersThisMonth,
            'reports' => $reportsThisMonth,
            'completed' => $ordersCompleted,
            'years' => $years,
        ]);
    }

    /**
     * @Route("/chart/earnings/{year}", name="earnings", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     * @param $year
     * @return JsonResponse
     * @throws \Exception
     */
    public function chartEarnings(string $year)
    {
        $datetime = new \DateTime("$year-01-01");
        $earnings = $this->dashboardService->earningsChartData($datetime);
        return new JsonResponse($earnings,Response::HTTP_OK);
    }

    /**
     * @Route("/chart/products", name="products_pie", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function chartProducts()
    {
        $pieData = $this->dashboardService->productsPieData();
        return new JsonResponse($pieData,Response::HTTP_OK);
    }
}
