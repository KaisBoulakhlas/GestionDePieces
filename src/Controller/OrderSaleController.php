<?php

namespace App\Controller;

use App\Repository\OrderSaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSaleController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/ordersSale", name="ordersale.index")
     * @param OrderSaleRepository $orderSaleRepository
     * @return Response
     */
    public function index(OrderSaleRepository $orderSaleRepository): Response
    {
        $ordersales = $orderSaleRepository->findAll();
        return $this->render('order_sale/index.html.twig', [
            'ordersales' => $ordersales,
        ]);
    }
}
