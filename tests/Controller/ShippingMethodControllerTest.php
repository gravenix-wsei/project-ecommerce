<?php

namespace App\Test\Controller;

use App\Entity\ShippingMethod;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShippingMethodControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/shipping/method/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(ShippingMethod::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ShippingMethod index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'shipping_method[name]' => 'Testing',
            'shipping_method[priceNet]' => 'Testing',
            'shipping_method[priceGross]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new ShippingMethod();
        $fixture->setName('My Title');
        $fixture->setPriceNet('My Title');
        $fixture->setPriceGross('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ShippingMethod');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new ShippingMethod();
        $fixture->setName('Value');
        $fixture->setPriceNet('Value');
        $fixture->setPriceGross('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'shipping_method[name]' => 'Something New',
            'shipping_method[priceNet]' => 'Something New',
            'shipping_method[priceGross]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/shipping/method/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getPriceNet());
        self::assertSame('Something New', $fixture[0]->getPriceGross());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new ShippingMethod();
        $fixture->setName('Value');
        $fixture->setPriceNet('Value');
        $fixture->setPriceGross('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/shipping/method/');
        self::assertSame(0, $this->repository->count([]));
    }
}
