<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Order;

interface OrderRepositoryInterface
{
    /**
     * @param Order $order
     * @return bool
     */
    public function create(Order $order): bool;

    /**
     * @param Order $order
     * @return bool
     */
    public function update(Order $order): bool;

    /**
     * @return array|string
     */
    public function listAll(): array;

    /**
     * @param $id
     * @return Order|null
     */
    public function findOne($id): ?Order;

    /**
     * @param Order $order
     * @return bool
     */
    public function remove(Order $order): bool;

    /**
     * @return array
     */
    public function getErrors(): array;
}