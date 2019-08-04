<?php


namespace AppBundle\Service;


use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;

interface ProductServiceInterface
{
    /**
     * @param Product $product
     * @return bool
     */
    public function add(Product $product): bool;

    /**
     * @param Product $product
     * @return bool
     */
    public function edit(Product $product): bool;

    /**
     * @return Product[]
     */
    public function listAll(): array;

    /**
     * @return Product[]
     */
    public function listActive(): array;

    /**
     * @param int $id
     * @return Product|null
     */
    public function getById($id): ?Product;

    /**
     * Returns [id => name] pairs
     *
     * @return array
     */
    public function getProductNames(): array;

}