<?php

namespace App\Controller;

use App\Entity\Range;
use App\Form\RangeType;
use App\Repository\RangeRepository;
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
     * @return Response
     */
    public function index(RangeRepository $rangeRepository): Response
    {
        $ranges = $rangeRepository->findBy(array(), array('id' => 'asc'));
        return $this->render('home/index.html.twig', [
            'ranges' => $ranges,
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
        $form = $this->createForm(RangeType::class, $range);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($range);
            $em->flush();
            $this->addFlash('success', "La gamme " . $range->getLibelle() . " a bien été créée avec succès.");

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
        $form = $this->createForm(RangeType::class, $range);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', "La gamme ". $range->getLibelle() . " a été modifiée avec succès.");
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
            $this->addFlash('success', "La gamme " . $range->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('home');
    }
}
