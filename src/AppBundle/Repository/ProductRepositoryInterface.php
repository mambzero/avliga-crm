<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Product;

interface ProductRepositoryInterface
{
    /**
     * @param Product $product
     * @return bool
     */
    public function insert(Product $product): bool;

    /**
     * @param Product $product
     * @return bool
     */
    public function update(Product $product): bool;

    /**
     * @return Product[]
     */
    public function listAll(): array;

    /**
     * @param int $id
     * @return Product|Object|null
     */
    public function findOne($id): ?Product;

    /**
     * Returns active products.
     * @return Product[]
     */
    public function listActive(): array;

}