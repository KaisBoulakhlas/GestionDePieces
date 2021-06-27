<?php

namespace App\Controller;

use App\Entity\PieceUsed;
use App\Entity\Range;
use App\Entity\RangeRealisation;
use App\Entity\Realisation;
use App\Form\RangeRealisationType;
use App\Repository\RangeRealisationRepository;
use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_OUVRIER")
 **/
class RangeRealisationController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/rangeRealisation/{id}/realisations", name="range.realisations.index")
     * @param RangeRealisation $rangeRealisation
     * @param RealisationRepository $realisationRepository
     * @return Response
     */
    public function index(RangeRealisation $rangeRealisation, RealisationRepository $realisationRepository): Response
    {
        $realisations = $realisationRepository->findAllRealisationsByRangeRealisation($rangeRealisation);
        return $this->render('range_realisation/index.html.twig', [
            'realisations' => $realisations,
            'rangeRealisation' => $rangeRealisation,
        ]);
    }

    /**
     * @Route("/range/{id}/rangeRealisation/add", name="range.realisation.add")
     * @param Range $range
     * @return Response
     */
        public function new(Range $range): Response
    {
        $em = $this->em;
        $rangeRealisation = new RangeRealisation();
        $operations = $range->getOperations();

        if($operations != null){
            foreach($operations as $operation){
                $realisation = new Realisation();
                $realisation->setOperation($operation);
                $realisation->setLibelle($operation->getLibelle());
                $realisation->setTime($operation->getTime());
                $realisation->setWorkstation($operation->getWorkStation());
                $realisation->setMachine($operation->getMachine());
                $rangeRealisation->addRealisation($realisation);
            }
        }
        if($range->getPiece() != null){
            $range->getPiece()->setQuantity($range->getPiece()->getQuantity() + 1);
        }

        $this->setQuantityPiece($range->getPiece()->getPieceUseds()->toArray());
        $rangeRealisation->setRange($range);
        $rangeRealisation->setUserWorkStation($range->getUserWorkstation());
        $em->persist($rangeRealisation);
        $em->flush();
        $this->addFlash('success_range_realisation_new', "La réalisation de gamme ". $range->getLibelle() . " a été créée avec succès.");

        return $this->redirectToRoute('home');
    }

    private function setQuantityPiece(array $pieceUseds)
    {
        /** @var PieceUsed $pieceUsed */
        foreach($pieceUseds as $pieceUsed){
            $piece = $pieceUsed->getPiece();
            $piece->setQuantity($piece->getQuantity() - $pieceUsed->getQuantity());
            $this->em->persist($piece);
        }
    }

    /**
     * @Route("rangeRealisation/edit/{id}", name="range.realisation.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param RangeRealisationRepository $rangeRealisationRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,RangeRealisationRepository $rangeRealisationRepository)
    {
        $em = $this->em;
        $rangeRealisation = $rangeRealisationRepository->find($id);
        $form = $this->createForm(RangeRealisationType::class, $rangeRealisation, [
            'action' => $this->generateUrl('range.realisation.edit', ['id' => $id])
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_range_realisation_edit', "La réalisation de gamme ". $rangeRealisation->getId() . " a été modifiée avec succès.");
            return $this->redirectToRoute('home');
        }

        return $this->render('range_realisation/edit.html.twig', [
            'rangeRealisation' => $rangeRealisation,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("rangeRealisation/delete/{id}", name="range.realisation.delete")
     * @param $id
     * @param Request $request
     * @param RangeRealisationRepository $rangeRealisationRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, RangeRealisationRepository $rangeRealisationRepository)
    {
        $em = $this->em;
        $rangeRealisation = $rangeRealisationRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $rangeRealisation->getId(), $request->request->get('_token'))){
            $em->remove($rangeRealisation);
            $em->flush();
            $this->addFlash('success_range_realisation_delete', "La réalisation de gamme a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('home');
    }
}
