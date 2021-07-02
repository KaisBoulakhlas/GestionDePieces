<?php

namespace App\Controller;

use App\Entity\OrderLine;
use App\Entity\OrderPurchase;
use App\Entity\OrderSale;
use App\Form\OrderPurchaseType;
use App\Form\OrderSaleType;
use App\Repository\OrderSaleRepository;
use App\Service\AddingQuantities;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_COMMERCIAL")
 **/
class OrderSaleController extends AbstractController
{
    protected $em;
    private $addingQuantities;

    public function __construct(EntityManagerInterface $entityManager,AddingQuantities $addingQuantities)
    {
        $this->addingQuantities = $addingQuantities;
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

    /**
     * @Route("/orderSale/add", name="ordersale.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $orderSale = new OrderSale();
        $em = $this->em;
        $form = $this->createForm(OrderSaleType::class, $orderSale, [
            'action' => $this->generateUrl("ordersale.add")
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addingQuantities->AddQuantityWhenTwoEstimateLinesIdentics($orderSale);
            $orderSale->setStatus(false);
            $em->persist($orderSale);
            $em->flush();
            $this->addFlash('success_ordersale_add', "La commande de vente " . $orderSale->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('ordersale.index');
        }

        return $this->render('order_sale/add.html.twig', [
            'orderSale' => $orderSale,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("ordersale/validate/{id}", name="ordersale.validate" , methods="GET|POST")
     * @param OrderSale $orderSale
     * @return Response
     */
    public function validateOrderSale(OrderSale $orderSale){
        $em = $this->em;
        $orderSale->setStatus(true);
        $orderSaleLines =  $orderSale->getOrderLines();
        foreach($orderSaleLines as $orderSaleLine){
            $piece = $orderSaleLine->getPiece();
            $piece->setQuantity($piece->getQuantity() - $orderSaleLine->getQuantity());
        }
        $em->persist($orderSale);
        $em->flush();
        $this->addFlash('success_ordersale_validate', "La commande de vente " . $orderSale->getLibelle() . " a bien été validé.");
        return $this->redirectToRoute('ordersale.index');
    }

    /**
     * @Route("/orderSale/{id}", name="ordersale.show")
     * @param OrderSale $orderSale
     * @param OrderSaleRepository $orderSaleRepository
     * @return Response
     */
    public function show(OrderSale $orderSale,OrderSaleRepository $orderSaleRepository): Response
    {
        $orderSale = $orderSaleRepository->findOneBy(['id' => $orderSale->getId()]);
        $totalLinePrice = [];

        foreach($orderSale->getOrderLines()->toArray() as $orderLine){
            array_push($totalLinePrice,$orderLine->getPrice() * $orderLine->getQuantity());
        }
        $totalPrice = array_sum($totalLinePrice);
        return $this->render('order_sale/show.html.twig', [
            'totalPrice' => $totalPrice,
            'orderSale' => $orderSale
        ]);
    }

    /**
     * @Route("/orderSale/edit/{id}", name="ordersale.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param OrderSaleRepository $orderSaleRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,OrderSaleRepository $orderSaleRepository)
    {
        $em = $this->em;
        $orderSale = $orderSaleRepository->find($id);
        $form = $this->createForm(OrderSaleType::class, $orderSale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addingQuantities->AddQuantityWhenTwoEstimateLinesIdentics($orderSale);
            $em->flush();
            $this->addFlash('success_ordersale_edit', "La commande de vente ". $orderSale->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('ordersale.index');
        };

        return $this->render('order_sale/edit.html.twig', [
            'orderSale' => $orderSale,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("orderSale/delete/{id}", name="ordersale.delete")
     * @param $id
     * @param Request $request
     * @param OrderSaleRepository $orderSaleRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, OrderSaleRepository $orderSaleRepository)
    {
        $em = $this->em;
        $orderSale = $orderSaleRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $orderSale->getId(), $request->request->get('_token'))){
            $em->remove($orderSale);
            $em->flush();
            $this->addFlash('success_ordersale_delete', "La commmande de vente " . $orderSale->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('ordersale.index');
    }
}
