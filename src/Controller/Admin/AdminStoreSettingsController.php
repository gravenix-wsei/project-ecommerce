<?php

namespace App\Controller\Admin;

use App\Form\StoreSettingsType;
use App\System\Settings\StoreSettingsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/settings')]
class AdminStoreSettingsController extends AbstractController
{
    public function __construct(
        private readonly StoreSettingsServiceInterface $storeSettingsService
    ) {
    }

    #[Route('/', name: 'app.admin.settings.index')]
    public function index(Request $request): Response
    {
        $settingsForm = $this->createForm(StoreSettingsType::class, [
            'doubleOptIn' => $this->storeSettingsService->isDoubleOptInAllowed(),
        ]);
        $settingsForm->handleRequest($request);
        if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
            $this->storeSettingsService->saveSettings($settingsForm);
            $this->addFlash('success', 'Successfully saved');
        }

        return $this->render('@project-ecommerce/administration/admin_settings/index.html.twig', [
            'controller_name' => 'AdminStoreSettingsController',
            'form' => $settingsForm,
        ]);
    }
}
