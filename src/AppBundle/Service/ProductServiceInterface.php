<?php


namespace AppBundle\Service;


use AppBundle\Entity\Product;

interface ProductServiceInterface
{
    /**
     * @param Product $product
     * @return bool
     */
    public function add(Product $product);

    /**
     * @param Product $product
     * @return bool
     */
    public function edit(Product $product);

    /**
     * @return Product[]
     */
    public function listAll();

    /**
     * @param int $id
     * @return Product|null
     */
    public function getById($id);
}