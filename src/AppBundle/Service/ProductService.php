<?php


namespace AppBundle\Service;


use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService implements ProductServiceInterface
{

    private $productRepository;
    private $fileService;

    public function __construct(ProductRepositoryInterface $productRepository, FileService $fileService)
    {
        $this->productRepository = $productRepository;
        $this->fileService = $fileService;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function add(Product $product)
    {

        $file = $product->getImage();

        if ($file!==null) {
            $fileUrl = $this->fileService->setFile($file)->setSubDir('products')->uploadFile()->getFileUrl();
            $product->setImageUrl($fileUrl);
        }

        return $this->productRepository->insert($product);
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function edit(Product $product)
    {

        $file = $product->getImage();

        if ($file instanceof UploadedFile) {
            $imageFromDb = $this->productRepository->findOne($product->getId())->getImageUrl();
            $fileUrl = $this->fileService->deleteFile($imageFromDb)->setSubDir('products')->setFile($file)->uploadFile()->getFileUrl();
            $product->setImageUrl($fileUrl);
        }

        return $this->productRepository->update($product);
    }

    /**
     * @return Product[]
     */
    public function listAll()
    {
        return $this->productRepository->listAll();
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function getById($id)
    {
        return $this->productRepository->findOne($id);
    }
}