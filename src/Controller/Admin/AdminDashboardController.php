<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'app.administration.dashboard')]
    public function index(): Response
    {
        return $this->render('administration/admin_dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
        ]);
    }
}
