<?php

namespace App\Controller\Admin;

use App\Entity\AdministrationUser;
use App\Form\AdministrationUserType;
use App\Repository\AdministrationUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users')]
class AdminUserController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ){
    }

    #[Route('/', name: 'app.admin.users.index', methods: ['GET'])]
    public function index(AdministrationUserRepository $administrationUserRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_user/index.html.twig', [
            'controller_name' => 'AdminUserController',
            'administration_users' => $administrationUserRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.users.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $administrationUser = new AdministrationUser();
        $form = $this->createForm(AdministrationUserType::class, $administrationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->prepareAdministrationUser($administrationUser);
            $entityManager->persist($administrationUser);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.users.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_user/new.html.twig', [
            'controller_name' => 'AdminUserController',
            'administration_user' => $administrationUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.users.show', methods: ['GET'])]
    public function show(AdministrationUser $administrationUser): Response
    {
        return $this->render('@project-ecommerce/administration/admin_user/show.html.twig', [
            'controller_name' => 'AdminUserController',
            'administration_user' => $administrationUser,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.users.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdministrationUser $administrationUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdministrationUserType::class, $administrationUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->prepareAdministrationUser($administrationUser);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.users.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_user/edit.html.twig', [
            'controller_name' => 'AdminUserController',
            'administration_user' => $administrationUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.users.delete', methods: ['POST'])]
    public function delete(Request $request, AdministrationUser $administrationUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$administrationUser->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($administrationUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.users.index', [], Response::HTTP_SEE_OTHER);
    }

    private function prepareAdministrationUser(AdministrationUser& $administrationUser): void
    {
        if ($this->passwordHasher->needsRehash($administrationUser)) {
            $hashedPassword = $this->passwordHasher->hashPassword($administrationUser, $administrationUser->getPassword());
            $administrationUser->setPassword($hashedPassword);
        }
    }
}
