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
            $piecesChildren = $form->get('piecesChildren')->getData();
            foreach ($piecesChildren as $pieceChildren) {
              $piece->addPiece($pieceChildren);
            }
            $em->persist($piece);
            $em->flush();
            $this->addFlash('success_piece_add', "La pièce " . $piece->getLibelle() . " a été créée avec succès.");
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
            $piecesChildren = $form->get('piecesChildren')->getData();
            foreach ($piecesChildren as $pieceChildren) {
                    $piece->addPiece($pieceChildren);
            }
            $piecesRemove = array_values(array_diff($piece->getPiecesChildren()->toArray(),$piecesChildren->toArray()));
            foreach($piecesRemove as $pieceRemove) {
                $piece->removePiece($pieceRemove);
            }
            $em->flush();
            $this->addFlash('success_piece_edit', "La pièce ". $piece->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('pieces.index');
        };

        $pieceChildrenId = array_map(function(Piece $piece){
            return $piece->getId();
        },$piece->getPiecesChildren()->toArray());

        return $this->render('piece/edit.html.twig', [
            'piece' => $piece,
            'pieceChildrenId' => $pieceChildrenId,
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
            $this->addFlash('success_piece_delete', "La pièce " . $piece->getLibelle() . " a été supprimée avec succès.");
        }

        return $this->redirectToRoute('pieces.index',array(
            'id' => $piece->getId()
        ));
    }

}
