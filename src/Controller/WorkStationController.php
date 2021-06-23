<?php

namespace App\Controller;

use App\Entity\WorkStation;
use App\Form\WorkStationType;
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
class WorkStationController extends AbstractController
{

    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/workstations", name="workstation.index")
     * @param WorkStationRepository $workStationRepository
     * @return Response
     */
    public function index(WorkStationRepository $workStationRepository) : Response
    {
        $workstations = $workStationRepository->findAll();
        return $this->render('work_station/index.html.twig', [
            'workstations' => $workstations,
        ]);
    }

    /**
     * @Route("/workstation/{id}/machines", name="workstation.machines")
     * @param WorkStation $workStation
     * @param WorkStationRepository $workStationRepository
     * @return Response
     */
    public function showMachines(WorkStation $workStation,WorkStationRepository $workStationRepository): Response
    {
        $workstation = $workStationRepository->findOneBy(['id' => $workStation->getId()]);
        return $this->render('work_station/show_machines.html.twig', [
            'workstation' => $workstation
        ]);
    }

    /**
     * @Route("/workstation/{id}/users", name="workstation.users")
     * @param WorkStation $workStation
     * @param WorkStationRepository $workStationRepository
     * @return Response
     */
    public function showUsers(WorkStation $workStation,WorkStationRepository $workStationRepository): Response
    {
        $workstation = $workStationRepository->findOneBy(['id' => $workStation->getId()]);
        return $this->render('work_station/show_users.html.twig', [
            'workstation' => $workstation
        ]);
    }

    /**
     * @Route("workstation/add", name="workstation.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $workstation = new WorkStation();
        $em = $this->em;
        $form = $this->createForm(WorkStationType::class, $workstation, [
            'action' => $this->generateUrl('workstation.add')
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $users = $form->get('users')->getData();
            foreach ($users as $user) {
                $workstation->addUser($user);
            }
            $em->persist($workstation);
            $em->flush();
            $this->addFlash('success_workstation_add', "Le poste de travail " . $workstation->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('workstation.index');
        }

        return $this->render('work_station/add.html.twig', [
            'workstation' => $workstation,
            'form_add_workstation' => $form->createView()
        ]);
    }

    /**
     * @Route("workstation/edit/{id}", name="workstation.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param WorkStationRepository $workStationRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,WorkStationRepository $workStationRepository)
    {
        $em = $this->em;
        $workstation = $workStationRepository->find($id);
        $form = $this->createForm(WorkStationType::class, $workstation, [
            'action' => $this->generateUrl('workstation.edit', ['id' => $id])
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $users = $form->get('users')->getData();
            foreach ($users as $user) {
                $workstation->addUser($user);
            }
            $usersRemove = array_values(array_diff($workstation->getUsers()->toArray(),$users->toArray()));
            foreach($usersRemove as $userRemove) {
                $workstation->removeUser($userRemove);
            }
            $em->flush();
            $this->addFlash('success_workstation_edit', "Le poste de travail ". $workstation->getLibelle() . " a été modifiée avec succès.");
            return $this->redirectToRoute('workstation.index');
        };

        return $this->render('work_station/edit.html.twig', [
            'workstation' => $workstation,
            'form_workstation' => $form->createView(),

        ]);
    }

    /**
     * @Route("workstation/delete/{id}", name="workstation.delete")
     * @param $id
     * @param Request $request
     * @param WorkStationRepository $workStationRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, WorkStationRepository $workStationRepository)
    {
        $em = $this->em;
        $workstation = $workStationRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $workstation->getId(), $request->request->get('_token'))){
            $em->remove($workstation);
            $em->flush();
            $this->addFlash('success_workstation_delete', "Le poste de travail " . $workstation->getLibelle() . " a bien été supprimée avec succès.");
        }

        return $this->redirectToRoute('workstation.index');
    }
}
