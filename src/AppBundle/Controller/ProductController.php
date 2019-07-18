<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use AppBundle\Service\ProductServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @var ProductServiceInterface
     */
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductServiceInterface $productService
     */
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/products/add", name="products_add")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->productService->add($product);
            return $this->redirectToRoute('products_list');
        }

        return $this->render('product/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{id}", name="products_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editProduct(Request $request, $id)
    {

        $product = $this->productService->getById($id);
        $form = $this->createForm(ProductType::class, $product);

        if ($product === null) {
            return $this->redirectToRoute('products_list');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->productService->edit($product);
            return $this->redirectToRoute('products_list');
        }

        return $this->render('product/edit.html.twig',[
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products", name="products_list")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function listProducts()
    {

        $products = $this->productService->listAll();

        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/products/view/{id}", name="products_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param int $id
     * @return Response
     */
    public function viewProduct($id)
    {
        $product = $this->productService->getById($id);

        if ($product === null) {
            return $this->redirectToRoute('products_list');
        }

        return $this->render('product/view.html.twig',[
            'product' => $product
        ]);
    }
}
