<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderPurchaseController extends AbstractController
{
    /**
     * @Route("/order/purchase", name="order_purchase")
     */
    public function index(): Response
    {
        return $this->render('order_purchase/index.html.twig', [
            'controller_name' => 'OrderPurchaseController',
        ]);
    }
}
