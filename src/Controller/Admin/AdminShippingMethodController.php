<?php

namespace App\Controller\Admin;

use App\Entity\ShippingMethod;
use App\Form\ShippingMethodType;
use App\Repository\ShippingMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/shipping-method')]
class AdminShippingMethodController extends AbstractController
{
    #[Route('/', name: 'app.admin.shipping-method.index', methods: ['GET'])]
    public function index(ShippingMethodRepository $shippingMethodRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_shipping_method/index.html.twig', [
            'controller_name' => 'AdminShippingMethodController',
            'shipping_methods' => $shippingMethodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.shipping-method.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shippingMethod = new ShippingMethod();
        $form = $this->createForm(ShippingMethodType::class, $shippingMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shippingMethod);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.shipping-method.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_shipping_method/new.html.twig', [
            'controller_name' => 'AdminShippingMethodController',
            'shipping_method' => $shippingMethod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.shipping-method.show', methods: ['GET'])]
    public function show(ShippingMethod $shippingMethod): Response
    {
        return $this->render('@project-ecommerce/administration/admin_shipping_method/show.html.twig', [
            'controller_name' => 'AdminShippingMethodController',
            'shipping_method' => $shippingMethod,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.shipping-method.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShippingMethod $shippingMethod, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShippingMethodType::class, $shippingMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.shipping-method.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_shipping_method/edit.html.twig', [
            'controller_name' => 'AdminShippingMethodController',
            'shipping_method' => $shippingMethod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.shipping-method.delete', methods: ['POST'])]
    public function delete(Request $request, ShippingMethod $shippingMethod, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shippingMethod->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shippingMethod);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.shipping-method.index', [], Response::HTTP_SEE_OTHER);
    }
}
