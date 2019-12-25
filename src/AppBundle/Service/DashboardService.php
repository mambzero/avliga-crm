<?php


namespace AppBundle\Service;


use AppBundle\Repository\OrderRepositoryInterface;
use AppBundle\Repository\ReportRepositoryInterface;
use AppBundle\Repository\ReturnRepositoryInterface;
use DateTime;
use Exception;

class DashboardService implements DashboardServiceInterface
{

    private $orderRepository;
    private $reportRepository;
    private $returnRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ReportRepositoryInterface $reportRepository,
        ReturnRepositoryInterface $returnRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->reportRepository = $reportRepository;
        $this->returnRepository = $returnRepository;
    }

    /**
     * @return array
     */
    public function productsPieData(): array
    {
        $orderedProducts = $this->orderRepository->getOrderedProductsCount();
        $reportedProducts = $this->reportRepository->getReportedProductsCount();
        $returnedProducts = $this->returnRepository->getReturnedProductsCount();

        $reported = $orderedProducts === 0 ? 0 : round(($reportedProducts / $orderedProducts) * 100);
        $returned = $orderedProducts === 0 ? 0 : round(($returnedProducts / $orderedProducts) * 100);

        $keys = ['Reported', 'Returned', 'In Clients'];
        $values = [
            $reported,
            $returned,
            $orderedProducts > 0 ? 100 - $reported - $returned : 0
        ];

        return [
            'keys' => $keys,
            'values' => $values
        ];

    }

    /**
     * @return array
     * @throws Exception
     */
    public function earningsChartData(): array
    {
        $datetime = new DateTime('now');
        $data = $this->reportRepository->getEarningsByMonths($datetime);
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $totals = array_fill(0,12, 0);

        foreach ($data as $row) {
            foreach ($months as $key => $month) {
                if ($month == $row['month']) {
                    $totals[$key] = floatval($row['total']);
                    break;
                }
            }
        }

        return [
            'months' => $months,
            'totals' => $totals
        ];
    }

    /**
     * @return int
     * @throws Exception
     */
    public function ordersThisMonth(): int
    {
        $datetime = new DateTime('now');
        $orders = $this->orderRepository->countOrdersByMonth($datetime);

        return $orders === null ? 0 : $orders;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function reportsThisMonth(): int
    {
        $datetime = new DateTime('now');
        $reports = $this->reportRepository->countReportsByMonth($datetime);

        return $reports === null ? 0 : $reports;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getEarningsForCurrentYear(): float
    {
        $datetime = new DateTime('now');
        $earnings = $this->reportRepository->getEarningsAnnual($datetime);

        if (is_null($earnings)) {
            return floatval(0);
        }

        return $earnings;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getEarningsForCurrentMonth(): float
    {
        $datetime = new DateTime('now');
        $earnings = $this->reportRepository->getEarningsMonthly($datetime);

        if (is_null($earnings)) {
            return floatval(0);
        }

        return $earnings;
    }

    /**
     * @return int
     */
    public function getOrdersCompletedPercentage(): int
    {
        $orderedProducts = $this->orderRepository->getOrderedProductsCount();
        $reportedProducts = $this->reportRepository->getReportedProductsCount();
        $returnedProducts = $this->returnRepository->getReturnedProductsCount();

        return $orderedProducts === 0 ? 0 : round((($reportedProducts + $returnedProducts) / $orderedProducts) * 100, 2);

    }
}