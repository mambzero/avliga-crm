<?php


namespace AppBundle\Service;


interface DashboardServiceInterface
{
    /**
     * @return array
     */
    public function productsPieData(): array;

    /**
     * @param \DateTime $dateTime
     * @return array
     */
    public function earningsChartData(\DateTime $dateTime): array;

    /**
     * @return int
     */
    public function ordersThisMonth(): int;

    /**
     * @return int
     */
    public function reportsThisMonth(): int;

    /**
     * @return float
     */
    public function getEarningsForCurrentYear(): float;

    /**
     * @return float
     */
    public function getEarningsForCurrentMonth(): float;

    /**
     * @return int
     */
    public function getOrdersCompletedPercentage(): int;

    /**
     * @return array
     */
    public function getChartAreaReportYears(): array;
}