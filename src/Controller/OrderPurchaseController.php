<?php

namespace App\Controller;

use App\Entity\OrderPurchase;
use App\Entity\Piece;
use App\Form\OrderPurchaseType;
use App\Repository\OrderPurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_COMMERCIAL")
 **/
class OrderPurchaseController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/orderPurchase/add", name="orderpurchase.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $orderPurchase = new OrderPurchase();
        $em = $this->em;
        $form = $this->createForm(OrderPurchaseType::class, $orderPurchase);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->AddQuantityWhenTwoPieceIdentics($orderPurchase);
            foreach ($orderPurchase->getOrderPurchaseLines() as $orderLine) {
                $orderLine->setPriceCatalog($orderLine->getPiece()->getPriceCatalogue());
            }
            $em->persist($orderPurchase);
            $em->flush();
            $this->addFlash('success_orderpuchase_add', "La commande d'achat " . $orderPurchase->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('home');
        }

        return $this->render('order_purchase/add.html.twig', [
            'orderPurchase' => $orderPurchase,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/orderPurchase/{id}", name="orderpurchase.show")
     * @param OrderPurchase $orderPurchase
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return Response
     */
    public function show(OrderPurchase $orderPurchase,OrderPurchaseRepository $orderPurchaseRepository): Response
    {
        $orderPurchase = $orderPurchaseRepository->findOneBy(['id' => $orderPurchase->getId()]);
        $totalLinePrice = [];

        foreach($orderPurchase->getOrderPurchaseLines()->toArray() as $orderPurchaseLine){
                array_push($totalLinePrice,$orderPurchaseLine->getPriceCatalog() * $orderPurchaseLine->getQuantity());
        }
        $totalPrice = array_sum($totalLinePrice);
        return $this->render('order_purchase/show.html.twig', [
            'totalPrice' => $totalPrice,
            'orderPurchase' => $orderPurchase
        ]);
    }

    /**
     * @Route("/price/{id}", name="price", methods="GET")
     * @param Piece $piece
     * @return JsonResponse
     */
    public function getPrice(Piece $piece)
    {
        return new JsonResponse($piece->getPriceCatalogue());
    }

    /**
     * @param OrderPurchase $orderPurchase
     * @return array
     */
    public function AddQuantityWhenTwoPieceIdentics(OrderPurchase $orderPurchase){
        $res = [];
        foreach ($orderPurchase->getOrderPurchaseLines() as $orderLine) {
            if (isset($res[$orderLine->getPiece()->getId()])) {
                $orderPurchase->removeOrderPurchaseLine($orderLine);
                $pieceUsed = $res[$orderLine->getPiece()->getId()];
                $pieceUsed->setQuantity($pieceUsed->getQuantity() + $orderLine->getQuantity());
                $orderPurchase->addOrderPurchaseLine($pieceUsed);
                continue;
            }
            $res[$orderLine->getPiece()->getId()] = $orderLine;
        }
        return $res;
    }

    /**
     * @Route("/orderPurchase/edit/{id}", name="orderpurchase.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,OrderPurchaseRepository $orderPurchaseRepository)
    {
        $em = $this->em;
        $orderPurchase = $orderPurchaseRepository->find($id);
        $form = $this->createForm(OrderPurchaseType::class, $orderPurchase);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->AddQuantityWhenTwoPieceIdentics($orderPurchase);
            foreach ($orderPurchase->getOrderPurchaseLines() as $orderLine) {
                $orderLine->setPriceCatalog($orderLine->getPiece()->getPriceCatalogue());
            }
            if($orderPurchase->getDateDeliveryReal() != null){
                foreach($orderPurchase->getOrderPurchaseLines() as $orderPurchaseLine){
                    $orderPurchaseLineQuantity = $orderPurchaseLine->getQuantity();
                    $pieceQuantity = $orderPurchaseLine->getPiece()->getQuantity();
                    $orderPurchaseLine->getPiece()->setQuantity($pieceQuantity + $orderPurchaseLineQuantity);
                }
            }
            $em->flush();
            $this->addFlash('success_range_edit', "La commande d'achat ". $orderPurchase->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('home');
        };

        return $this->render('order_purchase/edit.html.twig', [
            'orderPurchase' => $orderPurchase,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("orderPurchase/delete/{id}", name="orderpurchase.delete")
     * @param $id
     * @param Request $request
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, OrderPurchaseRepository $orderPurchaseRepository)
    {
        $em = $this->em;
        $orderPurchase = $orderPurchaseRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $orderPurchase->getId(), $request->request->get('_token'))){
            $em->remove($orderPurchase);
            $em->flush();
            $this->addFlash('success_range_delete', "La commmande d'achat " . $orderPurchase->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('home');
    }
}
