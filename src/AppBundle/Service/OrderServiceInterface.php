<?php


namespace AppBundle\Service;


use AppBundle\Entity\Order;

interface OrderServiceInterface
{
    /**
     * @param Order $order
     * @return bool
     */
    public function save(Order $order): bool;

    /**
     * @return Order[]
     */
    public function listAll(): array;

    /**
     * @param $id
     * @return Order|null
     */
    public function getById($id): ?Order;

    /**
     * @param Order $order
     * @return bool
     */
    public function delete(Order $order): bool;

    /**
     * @return array
     */
    public function getErrors(): array;
}