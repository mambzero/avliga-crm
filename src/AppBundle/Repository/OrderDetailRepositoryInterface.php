<?php


namespace AppBundle\Repository;


interface OrderDetailRepositoryInterface
{
    /**
     * @param int $id
     * @return int
     */
    public function getQuantity(int $id):int;
}