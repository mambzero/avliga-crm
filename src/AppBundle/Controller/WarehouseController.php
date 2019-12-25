<?php

namespace AppBundle\Controller;

use AppBundle\Service\WarehouseServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WarehouseController extends Controller
{

    private $warehouseService;

    public function __construct(WarehouseServiceInterface $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * @Route("/stocks", name="stocks")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function listStocks()
    {
        $stocks = $this->warehouseService->listStocks();

        return $this->render('warehouse/stocks.html.twig', [
            'products' => $stocks
        ]);
    }

}
