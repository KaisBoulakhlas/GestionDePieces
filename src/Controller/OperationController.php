<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
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
class OperationController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/operations", name="operations.index")
     * @param OperationRepository $operationRepository
     * @return Response
     */
    public function index(OperationRepository $operationRepository) : Response
    {
        $operations = $operationRepository->findAll();
        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
        ]);
    }


    /**
     * @Route("/operation/{id}/ranges", name="operation.ranges.show")
     * @param Operation $operation
     * @param OperationRepository $operationRepository
     * @return Response
     */
    public function showOperations(Operation $operation,OperationRepository $operationRepository): Response
    {
        $operation = $operationRepository->findOneBy(['id' => $operation->getId()]);
        return $this->render('operation/show_ranges.html.twig', [
            'operation' => $operation
        ]);
    }


    /**
     * @Route("operation/add", name="operation.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $operation = new Operation();
        $em = $this->em;
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ranges = $form->get('ranges')->getData();
            foreach ($ranges as $range) {
                $operation->addRange($range);
            }
            $em->persist($operation);
            $em->flush();
            $this->addFlash('success_operations', "L'opération " . $operation->getLibelle() . " a été créée avec succès.");
            return $this->redirectToRoute('operations.index');
        }

        return $this->render('operation/add.html.twig', [
            'operation' => $operation,
            'form_operation' => $form->createView()
        ]);
    }

    /**
     * @Route("operation/edit/{id}", name="operation.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param OperationRepository $operationRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request, OperationRepository $operationRepository)
    {
        $em = $this->em;
        $operation = $operationRepository->find($id);
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ranges = $form->get('ranges')->getData();
            foreach ($ranges as $range) {
                $operation->addRange($range);
            }
            $rangesRemove = array_values(array_diff($operation->getRanges()->toArray(),$ranges->toArray()));
            foreach($rangesRemove as $rangeRemove) {
                $operation->removeRange($rangeRemove);
            }
            $em->flush();
            $this->addFlash('success_operation_edit', "L'opération ". $operation->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('operations.index');
        };

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form_operation' => $form->createView(),

        ]);
    }

    /**
     * @Route("operation/delete/{id}", name="operation.delete")
     * @param $id
     * @param Request $request
     * @param OperationRepository $operationRepository
     * @return RedirectResponse
     */
    public function delete($id,Request $request,OperationRepository $operationRepository)
    {
        $em = $this->em;
        $operation = $operationRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $operation->getId(), $request->request->get('_token'))){
            $em->remove($operation);
            $em->flush();
            $this->addFlash('success_operation_delete', "L'opération " . $operation->getLibelle() . " a été supprimée avec succès.");
        }

        return $this->redirectToRoute('operations.index');
    }


}
