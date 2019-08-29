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
     * Returns active products.
     *
     * @param int|null $type
     * @return Product[]
     */
    public function listActive($type = null): array;

    /**
     * Return alphabetical array of active products.
     * @return Product[]
     */
    public function listAlphabetical(): array;

    /**
     * @param int $id
     * @return Product|Object|null
     */
    public function findOne($id): ?Product;

}