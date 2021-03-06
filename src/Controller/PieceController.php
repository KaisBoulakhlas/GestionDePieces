<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Form\OperationType;
use App\Form\PieceType;
use App\Repository\OperationRepository;
use App\Repository\PieceRepository;
use App\Repository\RangeRepository;
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
class PieceController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/pieces", name="pieces.index")
     * @param PieceRepository $pieceRepository
     * @return Response
     */
    public function index(PieceRepository $pieceRepository): Response
    {
        $pieces = $pieceRepository->findAll();
        return $this->render('piece/index.html.twig', [
            'pieces' => $pieces
        ]);
    }

    /**
     * @Route("/piece/composition/{id}", name="piece.composition")
     * @param Piece $piece
     * @param PieceRepository $pieceRepository
     * @return Response
     */
    public function showComposition(Piece $piece,PieceRepository $pieceRepository): Response
    {
        $piece = $pieceRepository->findOneBy(['id' => $piece->getId()]);
        return $this->render('piece/show.html.twig', [
            'piece' => $piece
        ]);
    }

    /**
     * @Route("/piece/add", name="piece.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $piece = new Piece();
        $em = $this->em;
        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //dd($form->get('pieceUseds')->getData());
            $em->persist($piece);
            $em->flush();
            $this->addFlash('success_piece_add', "La pi??ce " . $piece->getLibelle() . " a ??t?? cr????e avec succ??s.");
            return $this->redirectToRoute('pieces.index');
        }

        return $this->render('piece/add.html.twig', [
            'piece' => $piece,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/piece/edit/{id}", name="piece.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param PieceRepository $pieceRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,PieceRepository $pieceRepository)
    {
        $em = $this->em;
        $piece = $pieceRepository->find($id);
        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_piece_edit', "La pi??ce ". $piece->getLibelle() . " a ??t?? modifi??e avec succ??s.");
            return $this->redirectToRoute('pieces.index');
        };

        return $this->render('piece/edit.html.twig', [
            'piece' => $piece,
            'form_edit_piece' => $form->createView(),

        ]);
    }

    /**
     * @Route("/piece/delete/{id}", name="piece.delete")
     * @param $id
     * @param Request $request
     * @param PieceRepository $pieceRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, PieceRepository $pieceRepository)
    {
        $em = $this->em;
        $piece = $pieceRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $piece->getId(), $request->request->get('_token'))){
            $em->remove($piece);
            $em->flush();
            $this->addFlash('success_piece_delete', "La pi??ce " . $piece->getLibelle() . " a ??t?? supprim??e avec succ??s.");
        }

        return $this->redirectToRoute('pieces.index',array(
            'id' => $piece->getId()
        ));
    }

}
