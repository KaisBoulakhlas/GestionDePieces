<?php

namespace App\Controller;

use App\Entity\Range;
use App\Entity\User;
use App\Form\RangeType;
use App\Repository\OrderPurchaseRepository;
use App\Repository\RangeRealisationRepository;
use App\Repository\RangeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/home", name="home")
     * @param RangeRepository $rangeRepository
     * @param RangeRealisationRepository $rangeRealisationRepository
     * @param OrderPurchaseRepository $orderPurchaseRepository
     * @return Response
     */
    public function index(RangeRepository $rangeRepository,RangeRealisationRepository $rangeRealisationRepository,OrderPurchaseRepository $orderPurchaseRepository): Response
    {

        $ranges = $rangeRepository->findBy(array(), array('id' => 'asc'));
        $rangeRealisations = $rangeRealisationRepository->findAll();
        $orderPurchases = $orderPurchaseRepository->findAll();
        return $this->render('home/index.html.twig', [
            'ranges' => $ranges,
            'rangeRealisations' => $rangeRealisations,
            'orderPurchases' => $orderPurchases,
        ]);
    }

    public function findUsersByRole($role)
    {
        $workers = [];
        foreach ($this->em->getRepository(User::class)->findAll() as $user) {
            if(in_array($role, $user->getRoles())) {
                array_push($workers, $user);
            }
        }
        return $workers;
    }

    /**
     * @Route("/range/{id}/operations", name="range.operations.show")
     * @param Range $range
     * @param RangeRepository $rangeRepository
     * @return Response
     */
    public function showOperations(Range $range,RangeRepository $rangeRepository): Response
    {
        $range = $rangeRepository->findOneBy(['id' => $range->getId()]);
        return $this->render('home/show_operations.html.twig', [
            'range' => $range
        ]);
    }

    /**
     * @Route("/home/range/add", name="home.range.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $range = new Range();
        $em = $this->em;
        $form = $this->createForm(RangeType::class, $range, [
            'action' => $this->generateUrl('home.range.add')
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($range);
            $em->flush();
            $this->addFlash('success_range_add', "La gamme " . $range->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('home');
        }

        return $this->render('home/add.html.twig', [
            'range' => $range,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("home/range/edit/{id}", name="home.range.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param RangeRepository $rangeRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,RangeRepository $rangeRepository)
    {
        $em = $this->em;
        $range = $rangeRepository->find($id);
        $form = $this->createForm(RangeType::class, $range, [
            'action' => $this->generateUrl('home.range.edit', ['id' => $id])
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_range_edit', "La gamme ". $range->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('home');
        };

        return $this->render('home/edit.html.twig', [
            'range' => $range,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("home/range/delete/{id}", name="home.range.delete")
     * @param $id
     * @param Request $request
     * @param RangeRepository $rangeRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, RangeRepository $rangeRepository)
    {
        $em = $this->em;
        $range = $rangeRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $range->getId(), $request->request->get('_token'))){
            $em->remove($range);
            $em->flush();
            $this->addFlash('success_range_delete', "La gamme " . $range->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('home');
    }
}
