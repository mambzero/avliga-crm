<?php


namespace AppBundle\Service;


use AppBundle\Entity\Order;
use AppBundle\Repository\OrderRepositoryInterface;
use DateTime;
use Exception;

class OrderService implements OrderServiceInterface
{

    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
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

}