<?php


namespace AppBundle\Service;


use AppBundle\Entity\Order;
use AppBundle\Repository\OrderRepositoryInterface;
use AppBundle\Repository\ReportRepositoryInterface;
use DateTime;
use Exception;

class OrderService implements OrderServiceInterface
{

    private $orderRepository;
    private $reportRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, ReportRepositoryInterface $reportRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->reportRepository = $reportRepository;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function save(Order $order): bool
    {

        $details = $order->getDetails();
        foreach ($details as $detail) {
            if (is_null($detail->getOrder())) {
                $detail->setOrder($order);
            }
        }

        if (is_null($order->getId())) {
            return $this->orderRepository->create($order);
        } else {
            return $this->orderRepository->update($order);
        }
    }

    /**
     * @return Order[]
     */
    public function listAll(): array
    {
        return $this->orderRepository->listAll();
    }

    /**
     * @param $id
     * @return Order|null
     */
    public function getById($id): ?Order
    {
        return $this->orderRepository->findOne($id);
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function delete(Order $order): bool
    {
        return $this->orderRepository->remove($order);
    }

    public function getErrors():array
    {
        return $this->orderRepository->getErrors();
    }

    /**
     * Returns reported products percentage from ordered products
     * @return float
     */
    public function getOrdersCompletedPercentage(): float
    {
        $reportedProducts = $this->reportRepository->getReportedProductsCount();
        $orderedProducts = $this->orderRepository->getOrderedProductsCount();

        return floatval($reportedProducts / $orderedProducts) * 100;

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
}