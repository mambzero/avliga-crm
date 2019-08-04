<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderDetail;
use AppBundle\Form\OrderType;
use AppBundle\Service\OrderServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{

    private $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @Route("/orders", name="orders_list")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function listOrders()
    {

        $orders = $this->orderService->listAll();

        return $this->render('order/list.html.twig',[
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/orders/create", name="orders_create")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @return Response
     */
    public function createOrder(Request $request)
    {
        $order = new Order();
        $order->addDetail(new OrderDetail());
        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->orderService->save($order)) {
                return $this->redirectToRoute('orders_list');
            }
            return new Response(implode(', ',$this->orderService->getErrors()));
        }

        return $this->render('order/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/orders/edit/{id}", name="orders_edit")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editOrder(Request $request, $id)
    {
        $order = $this->orderService->getById($id);

        if (!$order) {
            return $this->redirectToRoute('orders_list');
        }

        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->orderService->save($order)) {
                return $this->redirectToRoute('orders_list');
            }
            return new Response(implode(', ',$this->orderService->getErrors()));
        }

        return $this->render('order/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/orders/delete/{id}", name="orders_delete")
     * @Security("has_role('ROLE_USER')")
     *
     * @param $id
     * @return RedirectResponse
     */

    public function deleteOrder($id)
    {
        $order = $this->orderService->getById($id);

        if ($order) {
            $this->orderService->delete($order);
        }

        return $this->redirectToRoute('orders_list');
    }

    /**
     * @Route("/orders/view/{id}", name="orders_view")
     * @Security("has_role('ROLE_USER')")
     *
     * @param $id
     * @return Response
     */
    public function viewOrder($id)
    {
        $order = $this->orderService->getById($id);

        if (!$order) {
            return $this->redirectToRoute('orders_list');
        }

        return $this->render('order/view.html.twig',[
            'order' => $order
        ]);
    }

}
