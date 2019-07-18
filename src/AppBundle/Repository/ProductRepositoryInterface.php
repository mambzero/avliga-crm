<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Product;

interface ProductRepositoryInterface
{
    /**
     * @param Product $product
     * @return bool
     */
    public function insert(Product $product);

    /**
     * @param Product $product
     * @return bool
     */
    public function update(Product $product);

    /**
     * @return Product[]
     */
    public function listAll();

    /**
     * @param int $id
     * @return Product|Object|null
     */
    public function findOne($id);

}