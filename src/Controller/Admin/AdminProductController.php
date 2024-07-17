<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product')]
class AdminProductController extends AbstractController
{
    #[Route('/', name: 'app.admin.product.index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_product/index.html.twig', [
            'controller_name' => 'AdminProductController',
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.product.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_product/new.html.twig', [
            'controller_name' => 'AdminProductController',
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.product.show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('@project-ecommerce/administration/admin_product/show.html.twig', [
            'controller_name' => 'AdminProductController',
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.product.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_product/edit.html.twig', [
            'controller_name' => 'AdminProductController',
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.product.delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.product.index', [], Response::HTTP_SEE_OTHER);
    }
}
