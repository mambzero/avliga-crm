<?php


namespace AppBundle\Repository;


use AppBundle\Entity\OrderDetail;

interface OrderDetailRepositoryInterface
{
    /**
     * @param int $id
     * @return int
     */
    public function getQuantity(int $id):int;

    /**
     * @param int $id
     * @return OrderDetail|Object|null
     */
    public function findOne(int $id): ?OrderDetail;
}