<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name: 'app.admin.category.index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_category/index.html.twig', [
            'controller_name' => 'AdminCategoryController',
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.category.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.category.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_category/new.html.twig', [
            'controller_name' => 'AdminCategoryController',
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.category.show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('@project-ecommerce/administration/admin_category/show.html.twig', [
            'controller_name' => 'AdminCategoryController',
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.category.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.category.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_category/edit.html.twig', [
            'controller_name' => 'AdminCategoryController',
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.category.delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.category.index', [], Response::HTTP_SEE_OTHER);
    }
}
