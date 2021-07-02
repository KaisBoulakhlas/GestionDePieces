<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Entity\Piece;
use App\Form\EstimateType;
use App\Repository\EstimateRepository;
use App\Service\AddingQuantities;
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
class EstimateController extends AbstractController
{
    protected $em;
    private $addingQuantities;

    public function __construct(EntityManagerInterface $entityManager,AddingQuantities $addingQuantities)
    {
        $this->addingQuantities = $addingQuantities;
        $this->em = $entityManager;
    }

    /**
     * @Route("/estimates", name="estimate.index")
     * @param EstimateRepository $estimateRepository
     * @return Response
     */
    public function index(EstimateRepository $estimateRepository): Response
    {
        $estimates = $estimateRepository->findAll();
        return $this->render('estimate/index.html.twig', [
            'estimates' => $estimates,
        ]);
    }

    /**
     * @Route("estimate/validate/{id}", name="estimate.validate" , methods="GET|POST")
     * @param Estimate $estimate
     * @return Response
     */
    public function validateEstimate(Estimate $estimate){
        $em = $this->em;
        $estimate->setStatus(true);
        $em->persist($estimate);
        $em->flush();
        $this->addFlash('success_estimate_validate', "Le devis " . $estimate->getTitle() . " a bien été validé.");
        return $this->redirectToRoute('estimate.index');
    }

    /**
     * @Route("estimate/add", name="estimate.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $estimate = new Estimate();
        $em = $this->em;
        $form = $this->createForm(EstimateType::class,$estimate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addingQuantities->AddQuantityWhenTwoPieceIdentics($estimate);
            foreach ($estimate->getEstimateLines() as $estimateLine) {
                $estimateLine->setPrice($estimateLine->getPiece()->getPrice());
            }
            $estimate->setStatus(false);
            $em->persist($estimate);
            $em->flush();
            $this->addFlash('success_estimate_add', "Le devis " . $estimate->getTitle() . " a bien été créé avec succès.");
            return $this->redirectToRoute('estimate.index');
        }

        return $this->render('estimate/add.html.twig', [
            'estimate' => $estimate,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/priceLivrable/{id}", name="priceLivrable", methods="GET")
     * @param Piece $piece
     * @return JsonResponse
     */
    public function getPrice(Piece $piece)
    {
        return new JsonResponse($piece->getPrice());
    }

    /**
     * @Route("/estimate/{id}", name="estimate.show")
     * @param Estimate $estimate
     * @param EstimateRepository $estimateRepository
     * @return Response
     */
    public function show(Estimate $estimate,EstimateRepository $estimateRepository): Response
    {
        $estimate = $estimateRepository->findOneBy(['id' => $estimate->getId()]);
        $totalLinePrice = [];

        foreach($estimate->getEstimateLines()->toArray() as $estimateLine){
            array_push($totalLinePrice,$estimateLine->getPrice() * $estimateLine->getQuantity());
        }
        $totalPrice = array_sum($totalLinePrice);
        return $this->render('estimate/show.html.twig', [
            'totalPrice' => $totalPrice,
            'estimate' => $estimate
        ]);
    }

    /**
     * @Route("estimate/edit/{id}", name="estimate.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param EstimateRepository $estimateRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,EstimateRepository $estimateRepository)
    {
        $em = $this->em;
        $estimate = $estimateRepository->find($id);
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addingQuantities->AddQuantityWhenTwoPieceIdentics($estimate);
            foreach ($estimate->getEstimateLines() as $estimateLine) {
                $estimateLine->setPrice($estimateLine->getPiece()->getPrice());
            }
            $em->flush();
            $this->addFlash('success_estimate_edit', "Le devis " . $estimate->getTitle() . " a été modifié avec succès.");
            return $this->redirectToRoute('estimate.index');
        };

        return $this->render('estimate/edit.html.twig', [
            'estimate' => $estimate,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("estimate/delete/{id}", name="estimate.delete")
     * @param $id
     * @param Request $request
     * @param EstimateRepository $estimateRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, EstimateRepository $estimateRepository)
    {
        $em = $this->em;
        $estimate = $estimateRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $estimate->getId(), $request->request->get('_token'))){
            $em->remove($estimate);
            $em->flush();
            $this->addFlash('success_estimate_delete', "Le devis " . $estimate->getTitle() .  " a bien été supprimé avec succès.");
        }

        return $this->redirectToRoute('estimate.index');
    }
}
