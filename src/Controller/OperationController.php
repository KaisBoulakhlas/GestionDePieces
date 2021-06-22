<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Range;
use App\Entity\WorkStation;
use App\Form\OperationType;
use App\Form\RangeType;
use App\Repository\OperationRepository;
use App\Repository\RangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_OUVRIER")
 **/
class OperationController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/range/{id}/operations", name="range.operations.index")
     * @param Range $range
     * @param OperationRepository $operationRepository
     * @return Response
     */
    public function index(Range $range,OperationRepository $operationRepository) : Response
    {
        $operations = $operationRepository->findAllOperationsByRange($range);
        return $this->render('operation/index.html.twig', [
            'range' => $range,
            'operations' => $operations,
        ]);
    }


    /**
     * @Route("/range/{id}/operation/add", name="range.operation.add" , methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param RangeRepository $rangeRepository
     * @return RedirectResponse|Response
     */
    public function new($id,Request $request,RangeRepository $rangeRepository)
    {
        $operation = new Operation();
        $em = $this->em;
        $range = $rangeRepository->find($id);
        $operation->addRange($range);
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($operation);
            $em->flush();
            $this->addFlash('success_operations', "L'opération " . $operation->getLibelle() . " a été créée avec succès.");
            return $this->redirectToRoute('range.operations.index',array(
                'id' => $range->getId()
            ));
        }

        return $this->render('operation/add.html.twig', [
            'operation' => $operation,
            'range' => $range,
            'form_operation' => $form->createView()
        ]);
    }

    /**
     * @Route("/range/{id}/operation/edit/{operationId}", name="range.operation.edit", methods="GET|POST")
     * @param $id
     * @param $operationId
     * @param Request $request
     * @param RangeRepository $rangeRepository
     * @param OperationRepository $operationRepository
     * @return RedirectResponse|Response
     */
    public function edit($id,$operationId, Request $request,RangeRepository $rangeRepository, OperationRepository $operationRepository)
    {
        $em = $this->em;
        $range = $rangeRepository->find($id);
        $operation = $operationRepository->find($operationId);
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_operation_edit', "L'opération ". $operation->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('range.operations.index',array(
                'id' => $range->getId()
            ));
        };

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form_operation' => $form->createView(),

        ]);
    }

    /**
     * @Route("/range/{id}/operation/delete/{operationId}", name="range.operation.delete")
     * @param $id
     * @param $operationId
     * @param Request $request
     * @param RangeRepository $rangeRepository
     * @param OperationRepository $operationRepository
     * @return RedirectResponse
     */
    public function delete($id,$operationId, Request $request, RangeRepository $rangeRepository,OperationRepository $operationRepository)
    {
        $em = $this->em;
        $range = $rangeRepository->find($id);
        $operation = $operationRepository->find($operationId);
        if($this->isCsrfTokenValid('delete' . $operation->getId(), $request->request->get('_token'))){
            $em->remove($operation);
            $em->flush();
            $this->addFlash('success_operation_delete', "L'opération " . $operation->getLibelle() . " a été supprimée avec succès.");
        }

        return $this->redirectToRoute('range.operations.index',array(
            'id' => $range->getId()
        ));
    }


}
