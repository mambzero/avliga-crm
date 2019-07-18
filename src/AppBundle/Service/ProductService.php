<?php


namespace AppBundle\Service;


use AppBundle\Repository\ProductRepositoryInterface;

class ProductService implements ProductRepositoryInterface
{

    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

}