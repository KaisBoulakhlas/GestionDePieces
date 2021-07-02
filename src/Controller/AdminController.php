<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 **/
class AdminController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/users", name="users.index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository) : Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }



    /**
     * @Route("user/add", name="user.add" , methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $user = new User();
        $em = $this->em;
        $form = $this->createForm(AdminUserType::class, $user, [
            'roles' => $user->getRoles()
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();
            $this->addFlash('success_user_add', "L'utilisateur " . $user->getUsername() . " a bien été créée avec succès.");
            return $this->redirectToRoute('users.index');
        }

        return $this->render('admin/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("user/edit/{id}", name="user.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @return RedirectResponse|Response
     */
    public function edit($id, Request $request,UserRepository $userRepository)
    {
        $em = $this->em;
        $user = $userRepository->find($id);
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success_user_edit', "L'utilisateur ". $user->getUsername() . " a été modifié avec succès.");
            return $this->redirectToRoute('users.index');
        };

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("user/delete/{id}", name="user.delete")
     * @param $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @return RedirectResponse
     */
    public function delete($id, Request $request, UserRepository $userRepository)
    {
        $em = $this->em;
        $user = $userRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))){
            $em->remove($user);
            $em->flush();
            $this->addFlash('success_user_delete', "L'utilisateur " . $user->getUsername() . " a bien été supprimé avec succès.");
        }

        return $this->redirectToRoute('users.index');
    }
}
