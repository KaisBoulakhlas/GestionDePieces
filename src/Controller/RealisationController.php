<?php

namespace App\Controller;

use App\Form\RangeRealisationType;
use App\Form\RealisationType;
use App\Repository\RangeRealisationRepository;
use App\Repository\RealisationRepository;
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
class RealisationController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("rangeRealisation/{id}/edit/{realisationId}", name="range_realisation.realisation.edit", methods="GET|POST")
     * @param $id
     * @param $realisationId
     * @param Request $request
     * @param RangeRealisationRepository $rangeRealisationRepository
     * @param RealisationRepository $realisationRepository
     * @return RedirectResponse|Response
     */
    public function edit($id,$realisationId, Request $request,RangeRealisationRepository $rangeRealisationRepository,RealisationRepository $realisationRepository)
    {
        $em = $this->em;
        $rangeRealisation = $rangeRealisationRepository->find($id);
        $realisation = $realisationRepository->find($realisationId);
        $form = $this->createForm(RealisationType::class, $realisation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_rangeRealisation_realisation_edit', "La réalisation". $realisation->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('range.realisations.index',array(
                'id' => $rangeRealisation->getId()
            ));
        };

        return $this->render('realisation/edit.html.twig', [
            'realisation' => $realisation,
            'form_realisation' => $form->createView(),

        ]);
    }

    /**
     * @Route("rangeRealisation/{id}/delete/{realisationId}", name="rangeRealisation.realisation.delete")
     * @param $id
     * @param Request $request
     * @param RangeRealisationRepository $rangeRealisationRepository
     * @return RedirectResponse
     */
    public function delete($id, $realisationId, Request $request, RangeRealisationRepository $rangeRealisationRepository, RealisationRepository $realisationRepository)
    {
        $em = $this->em;
        $rangeRealisation = $rangeRealisationRepository->find($id);
        $realisation = $realisationRepository->find($realisationId);
        if($this->isCsrfTokenValid('delete' . $realisation->getId(), $request->request->get('_token'))){
            $em->remove($realisation);
            $em->flush();
            $this->addFlash('success_rangeRealisation_realisation_delete', "La réalisation ". $realisation->getLibelle() ." a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('range.realisations.index',array(
            'id' => $rangeRealisation->getId()
        ));
    }
}
