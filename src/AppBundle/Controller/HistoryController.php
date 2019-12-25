<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderServiceInterface;
use AppBundle\Service\ReportServiceInterface;
use AppBundle\Service\ReturnServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends Controller
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    private $orderService;
    private $reportService;
    private $returnService;

    public function __construct(
        OrderServiceInterface $orderService,
        ReportServiceInterface $reportService,
        ReturnServiceInterface $returnService
    )
    {
        $this->orderService = $orderService;
        $this->reportService = $reportService;
        $this->returnService = $returnService;
    }

    /**
     * @Route("/history/order/{id}", name="order_info", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param int $id
     * @return JsonResponse
     */
    public function orderInfo(int $id)
    {
        $response = [];
        $order = $this->orderService->getById($id);
        if ($order) {
            $response['items'] = [];
            foreach ($order->getDetails() as $detail) {
                $product = [
                    'title' => $detail->getProduct()->getTitle(),
                    'quantity' => $detail->getQuantity(),
                    'price' => $this->formatPrice($detail->getPrice()),
                    'discount' => $detail->getDiscount().'%',
                    'sum' => $this->formatPrice($detail->getSum()),
                    'total' => $this->formatPrice($detail->getTotal())
                ];
                array_push($response['items'],$product);
                $this->sortProducts($response['items']);
            }
            $response['total'] = $this->formatPrice($order->getTotal());
            $response['date'] = $order->getDateAdded()->format(self::DATE_FORMAT);
            $code = Response::HTTP_OK;
        } else {
            $response['error'] = 'Order does not exist';
            $code = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($response,$code);
    }

    /**
     * @Route("/history/report/{id}", name="report_info", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param int $id
     * @return JsonResponse
     */
    public function reportInfo(int $id)
    {
        $response = [];
        $report = $this->reportService->getById($id);
        if ($report) {
            $response['items'] = [];
            foreach ($report->getDetails() as $detail) {
                $product = [
                    'title' => $detail->getProduct()->getTitle(),
                    'quantity' => $detail->getQuantity(),
                    'price' => $this->formatPrice($detail->getPrice()),
                    'discount' => $detail->getDiscount().'%',
                    'sum' => $this->formatPrice($detail->getSum()),
                    'total' => $this->formatPrice($detail->getTotal())
                ];
                array_push($response['items'],$product);
                $this->sortProducts($response['items']);
            }
            $response['total'] = $this->formatPrice($report->getTotal());
            $response['date'] = $report->getDateAdded()->format(self::DATE_FORMAT);
            $code = Response::HTTP_OK;
        } else {
            $response['error'] = 'Report does not exist';
            $code = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($response,$code);
    }

    /**
     * @Route("/history/return/{id}", name="return_info", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param int $id
     * @return JsonResponse
     */
    public function returnInfo(int $id)
    {
        $response = [];
        $return = $this->returnService->getById($id);
        if ($return) {
            $response['items'][0] = [
                'title' => $return->getProduct()->getTitle(),
                'quantity' => $return->getQuantity()
            ];
            $response['date'] = $return->getDateAdded()->format(self::DATE_FORMAT);
            $code = Response::HTTP_OK;
        } else {
            $response['error'] = 'Return does not exist';
            $code = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($response,$code);
    }

    private function formatPrice(float $number)
    {
        return number_format($number, 2, '.', '');
    }

    private function sortProducts(&$array)
    {
        usort($array, function ($product1,$product2) {
            return $product1['title'] <=> $product2['title'];
        });
    }
}
