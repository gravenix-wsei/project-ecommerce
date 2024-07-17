<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/customer')]
class AdminCustomerController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ){
    }
    #[Route('/', name: 'app.admin.customer.index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('@project-ecommerce/administration/admin_customer/index.html.twig', [
            'controller_name' => 'AdminCustomerController',
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app.admin.customer.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->prepareCustomerToSave($customer);
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.customer.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_customer/new.html.twig', [
            'controller_name' => 'AdminCustomerController',
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.customer.show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('@project-ecommerce/administration/admin_customer/show.html.twig', [
            'controller_name' => 'AdminCustomerController',
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app.admin.customer.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->prepareCustomerToSave($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app.admin.customer.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@project-ecommerce/administration/admin_customer/edit.html.twig', [
            'controller_name' => 'AdminCustomerController',
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app.admin.customer.delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app.admin.customer.index', [], Response::HTTP_SEE_OTHER);
    }

    private function prepareCustomerToSave(Customer& $customer): void
    {
        if ($this->passwordHasher->needsRehash($customer)) {
            $hashedPassword = $this->passwordHasher->hashPassword($customer, $customer->getPassword());
            $customer->setPassword($hashedPassword);
        }
    }
}
