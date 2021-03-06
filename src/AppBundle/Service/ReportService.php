<?php


namespace AppBundle\Service;


use AppBundle\Entity\Report;
use AppBundle\Entity\ReportDetail;
use AppBundle\Repository\ProductRepositoryInterface;
use AppBundle\Repository\ReportRepositoryInterface;

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

        foreach ($report->getDetails() as $detail) {
            if (is_null($detail->getQuantity())) {
                $report->removeDetail($detail);
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
        $products = $this->productRepository->listAlphabetical();

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

}