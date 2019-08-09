<?php


namespace AppBundle\Service;


use AppBundle\Entity\Report;
use AppBundle\Entity\ReportDetail;
use AppBundle\Repository\OrderRepositoryInterface;
use AppBundle\Repository\ProductRepositoryInterface;
use AppBundle\Repository\ReportRepositoryInterface;
use DateTime;
use Exception;
use Symfony\Component\Validator\Constraints\Date;

class ReportService implements ReportServiceInterface
{

    private $reportRepository;
    private $productRepository;

    public function __construct(ReportRepositoryInterface$reportRepository, ProductRepositoryInterface $productRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Report $report
     * @return bool
     */
    public function save(Report $report): bool
    {
        $reportId = $report->getId();

        foreach ($report->getDetails() as $key => $detail) {
            if (is_null($detail->getQuantity())) {
                $report->getDetails()->remove($key);
            }
        }

        if (is_null($reportId)) {
            return $this->reportRepository->insert($report);
        } else {
            return $this->reportRepository->update($report);
        }
    }

    /**
     * @param Report $report
     * @return bool
     */
    public function delete(Report $report): bool
    {
        return $this->reportRepository->delete($report);
    }

    /**
     * @return Report[]
     */
    public function listAll(): array
    {
        return $this->reportRepository->listAll();
    }

    /**
     * @param int $id
     * @return Report|Object|null
     */
    public function getById(int $id): ?Report
    {
        return $this->reportRepository->findOne($id);
    }

    /**
     * @param int|null $id
     * @return Report|null
     */
    public function getInstance(int $id = null): ?Report
    {
        $report = $id === null ? new Report() : $this->getById($id);
        $products = $this->productRepository->listActive();

        if (!$report) {
            return null;
        }

        foreach ($products as $product) {
            $reportDetail = new ReportDetail();
            $reportDetail->setProduct($product);
            $reportDetail->setPrice($product->getPrice());
            if (!is_null($id)) {
                $reportDetail->setDiscount($report->getClient()->getDiscount());
            }
            if (!$report->hasDetailWithProduct($product) || is_null($id)) {
                $report->addDetail($reportDetail);
            }
        }

        return $report;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getEarningsByMonths(): array
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

}