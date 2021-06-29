<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Form\ProviderType;
use App\Repository\ProviderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProviderController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/providers", name="provider.index")
     * @param ProviderRepository $providerRepository
     * @return Response
     */
    public function index(ProviderRepository $providerRepository): Response
    {
        $providers = $providerRepository->findAll();
        return $this->render('provider/index.html.twig', [
            'providers' => $providers,
        ]);
    }

    /**
     * @Route("provider/add", name="provider.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $provider = new Provider();
        $em = $this->em;
        $form = $this->createForm(ProviderType::class,$provider);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($provider);
            $em->flush();
            $this->addFlash('success_provider_add', "Le fournisseur " . $provider->getLibelle() . " a bien été créée avec succès.");
            return $this->redirectToRoute('provider.index');
        }

        return $this->render('provider/add.html.twig', [
            'provider' => $provider,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("provider/edit/{id}", name="provider.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param ProviderRepository $providerRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,ProviderRepository $providerRepository)
    {
        $em = $this->em;
        $provider = $providerRepository->find($id);
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_provider_edit', "Le fournisseur ". $provider->getLibelle() . " a été modifié avec succès.");
            return $this->redirectToRoute('provider.index');
        };

        return $this->render('provider/edit.html.twig', [
            'provider' => $provider,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("provider/delete/{id}", name="provider.delete")
     * @param $id
     * @param Request $request
     * @param ProviderRepository $providerRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, ProviderRepository $providerRepository)
    {
        $em = $this->em;
        $provider = $providerRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $provider->getId(), $request->request->get('_token'))){
            $em->remove($provider);
            $em->flush();
            $this->addFlash('success_provider_delete', "Le fournisseur " . $provider->getLibelle() . " a bien été supprimé avec succès.");
        }

        return $this->redirectToRoute('provider.index');
    }
}
