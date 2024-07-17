<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/promotion')]
class AdminPromotionController extends AbstractController
{
    #[Route('/', name: 'app.admin.promotion.index', methods: ['GET'])]
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_promotion/index.html.twig', [
            'controller_name' => 'AdminPromotionController',
            'promotions' => $promotionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.promotion.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.promotion.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_promotion/new.html.twig', [
            'controller_name' => 'AdminPromotionController',
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.promotion.show', methods: ['GET'])]
    public function show(Promotion $promotion): Response
    {
        return $this->render('@project-ecommerce/administration/admin_promotion/show.html.twig', [
            'controller_name' => 'AdminPromotionController',
            'promotion' => $promotion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.promotion.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.promotion.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_promotion/edit.html.twig', [
            'controller_name' => 'AdminPromotionController',
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.promotion.delete', methods: ['POST'])]
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.promotion.index', [], Response::HTTP_SEE_OTHER);
    }
}
