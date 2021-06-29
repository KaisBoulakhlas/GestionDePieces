<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/customers", name="customer.index")
     * @param CustomerRepository $customerRepository
     * @return Response
     */
    public function index(CustomerRepository $customerRepository): Response
    {
        $customers = $customerRepository->findAll();
        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }

    /**
     * @Route("customer/add", name="customer.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $customer = new Customer();
        $em = $this->em;
        $form = $this->createForm(CustomerType::class,$customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($customer);
            $em->flush();
            $this->addFlash('success_customer_add', "Le client " . $customer->getName() . $customer->getFirstname() . " a bien été créé avec succès.");
            return $this->redirectToRoute('customer.index');
        }

        return $this->render('customer/add.html.twig', [
            'customer' => $customer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("customer/edit/{id}", name="customer.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,CustomerRepository $customerRepository)
    {
        $em = $this->em;
        $customer = $customerRepository->find($id);
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_customer_edit', "Le client " . $customer->getName() . $customer->getFirstname() .  " a été modifié avec succès.");
            return $this->redirectToRoute('customer.index');
        };

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("customer/delete/{id}", name="customer.delete")
     * @param $id
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, CustomerRepository $customerRepository)
    {
        $em = $this->em;
        $customer = $customerRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))){
            $em->remove($customer);
            $em->flush();
            $this->addFlash('success_customer_delete', "Le client " . $customer->getName() . $customer->getFirstname() .  " a bien été supprimé avec succès.");
        }

        return $this->redirectToRoute('customer.index');
    }
}
