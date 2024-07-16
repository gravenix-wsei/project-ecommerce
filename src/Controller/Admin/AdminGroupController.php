<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/group')]
class AdminGroupController extends AbstractController
{
    #[Route('/', name: 'app.admin.group.index', methods: ['GET'])]
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_group/index.html.twig', [
            'controller_name' => 'AdminGroupController',
            'groups' => $groupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.group.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.group.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_group/new.html.twig', [
            'controller_name' => 'AdminGroupController',
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.group.show', methods: ['GET'])]
    public function show(Group $group): Response
    {
        return $this->render('@project-ecommerce/administration/admin_group/show.html.twig', [
            'controller_name' => 'AdminGroupController',
            'group' => $group,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.group.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.group.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_group/edit.html.twig', [
            'controller_name' => 'AdminGroupController',
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.group.delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.group.index', [], Response::HTTP_SEE_OTHER);
    }
}
