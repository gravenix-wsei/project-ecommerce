<?php declare(strict_types=1);

namespace App\System\Settings;

use App\Entity\StoreSetting;
use App\Repository\StoreSettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class StoreSettingsService implements StoreSettingsServiceInterface
{
    public const SETTING_DOUBLE_OPTIN = 'doubleOptIn';

    /** @var StoreSetting[] $configOptions */
    private array $configOptions = [];

    private bool $doubleOptIn = false;

    private bool $loaded = false;

    public function __construct(
        private readonly StoreSettingRepository $storeSettingRepository,
        private readonly EntityManagerInterface $entityManager
    ){
        $this->load();
    }

    public function isDoubleOptInAllowed(): bool
    {
        return $this->doubleOptIn;
    }

    public function saveSettings(FormInterface $settingsForm): void
    {
        foreach ($settingsForm->getData() as $key => $value) {
            $setting = $this->storeSettingRepository->findByKey($key);
            if ($setting === null) {
                continue;
            }

            if (\is_bool($value)) {
                $value = $value ? '1' : '0';
            }
            $setting->setValue((string) $value);
        }

        $this->entityManager->flush();
    }

    protected function load(): void
    {
        if (!$this->loaded) {
            $this->configOptions = $this->storeSettingRepository->findAll();
            /** @var StoreSetting $setting */
            foreach ($this->configOptions as $setting) {
                $this->initOption($setting);
            }
            $this->loaded = true;
        }
    }

    private function initOption(StoreSetting $setting): void
    {
        switch ($setting->getKey()) {
            case self::SETTING_DOUBLE_OPTIN:
                $this->doubleOptIn = (bool) $setting->getValue();
                break;
        }
    }
}