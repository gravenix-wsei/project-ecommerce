<?php

namespace App\Test\Controller;

use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PromotionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/promotion/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Promotion::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Promotion index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'promotion[name]' => 'Testing',
            'promotion[validSince]' => 'Testing',
            'promotion[validUntil]' => 'Testing',
            'promotion[code]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Promotion();
        $fixture->setName('My Title');
        $fixture->setValidSince('My Title');
        $fixture->setValidUntil('My Title');
        $fixture->setCode('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Promotion');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Promotion();
        $fixture->setName('Value');
        $fixture->setValidSince('Value');
        $fixture->setValidUntil('Value');
        $fixture->setCode('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'promotion[name]' => 'Something New',
            'promotion[validSince]' => 'Something New',
            'promotion[validUntil]' => 'Something New',
            'promotion[code]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/promotion/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getValidSince());
        self::assertSame('Something New', $fixture[0]->getValidUntil());
        self::assertSame('Something New', $fixture[0]->getCode());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Promotion();
        $fixture->setName('Value');
        $fixture->setValidSince('Value');
        $fixture->setValidUntil('Value');
        $fixture->setCode('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/promotion/');
        self::assertSame(0, $this->repository->count([]));
    }
}
