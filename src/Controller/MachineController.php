<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Form\MachineType;
use App\Form\WorkStationType;
use App\Repository\MachineRepository;
use App\Repository\WorkStationRepository;
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
class MachineController extends AbstractController
{

    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/machines", name="machine.index")
     * @param MachineRepository $machineRepository
     * @return Response
     */
    public function index(MachineRepository $machineRepository): Response
    {

        $machines = $machineRepository->findBy(array(), array('libelle' => 'asc'));
        return $this->render('machine/index.html.twig', [
            'machines' => $machines,
        ]);
    }

    /**
     * @Route("machine/add", name="machine.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $machine = new Machine();
        $em = $this->em;
        $form = $this->createForm(MachineType::class, $machine, [
            'action' => $this->generateUrl('machine.add')
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($machine);
            $em->flush();
            $this->addFlash('success_machine_add', "La machine " . $machine->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('machine.index');
        }

        return $this->render('machine/add.html.twig', [
            'machine' => $machine,
            'form_machine' => $form->createView()
        ]);
    }

    /**
     * @Route("machine/edit/{id}", name="machine.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param MachineRepository $machineRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,MachineRepository $machineRepository)
    {
        $em = $this->em;
        $machine = $machineRepository->find($id);
        $form = $this->createForm(MachineType::class, $machine, [
            'action' => $this->generateUrl('machine.edit', ['id' => $id])
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_machine_edit', "La machine ". $machine->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('machine.index');
        };

        return $this->render('machine/edit.html.twig', [
            'machine' => $machine,
            'form_machine' => $form->createView(),

        ]);
    }

    /**
     * @Route("machine/delete/{id}", name="machine.delete")
     * @param $id
     * @param Request $request
     * @param MachineRepository $machineRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, MachineRepository $machineRepository)
    {
        $em = $this->em;
        $machine = $machineRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $machine->getId(), $request->request->get('_token'))){
            $em->remove($machine);
            $em->flush();
            $this->addFlash('success_machine_delete', "La machine " . $machine->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('machine.index');
    }
}
