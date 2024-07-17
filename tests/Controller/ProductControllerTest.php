<?php

namespace App\Test\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/product/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Product::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'product[name]' => 'Testing',
            'product[stock]' => 'Testing',
            'product[description]' => 'Testing',
            'product[priceNet]' => 'Testing',
            'product[priceGross]' => 'Testing',
            'product[isPhysical]' => 'Testing',
            'product[categories]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setName('My Title');
        $fixture->setStock('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPriceNet('My Title');
        $fixture->setPriceGross('My Title');
        $fixture->setIsPhysical('My Title');
        $fixture->setCategories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setName('Value');
        $fixture->setStock('Value');
        $fixture->setDescription('Value');
        $fixture->setPriceNet('Value');
        $fixture->setPriceGross('Value');
        $fixture->setIsPhysical('Value');
        $fixture->setCategories('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'product[name]' => 'Something New',
            'product[stock]' => 'Something New',
            'product[description]' => 'Something New',
            'product[priceNet]' => 'Something New',
            'product[priceGross]' => 'Something New',
            'product[isPhysical]' => 'Something New',
            'product[categories]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/product/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getStock());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getPriceNet());
        self::assertSame('Something New', $fixture[0]->getPriceGross());
        self::assertSame('Something New', $fixture[0]->getIsPhysical());
        self::assertSame('Something New', $fixture[0]->getCategories());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setName('Value');
        $fixture->setStock('Value');
        $fixture->setDescription('Value');
        $fixture->setPriceNet('Value');
        $fixture->setPriceGross('Value');
        $fixture->setIsPhysical('Value');
        $fixture->setCategories('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/product/');
        self::assertSame(0, $this->repository->count([]));
    }
}
