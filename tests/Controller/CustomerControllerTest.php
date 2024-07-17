<?php

namespace App\Test\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/customer/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Customer::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Customer index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'customer[email]' => 'Testing',
            'customer[password]' => 'Testing',
            'customer[firstName]' => 'Testing',
            'customer[lastName]' => 'Testing',
            'customer[street]' => 'Testing',
            'customer[zipcode]' => 'Testing',
            'customer[city]' => 'Testing',
            'customer[country]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Customer();
        $fixture->setEmail('My Title');
        $fixture->setPassword('My Title');
        $fixture->setFirstName('My Title');
        $fixture->setLastName('My Title');
        $fixture->setStreet('My Title');
        $fixture->setZipcode('My Title');
        $fixture->setCity('My Title');
        $fixture->setCountry('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Customer');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Customer();
        $fixture->setEmail('Value');
        $fixture->setPassword('Value');
        $fixture->setFirstName('Value');
        $fixture->setLastName('Value');
        $fixture->setStreet('Value');
        $fixture->setZipcode('Value');
        $fixture->setCity('Value');
        $fixture->setCountry('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'customer[email]' => 'Something New',
            'customer[password]' => 'Something New',
            'customer[firstName]' => 'Something New',
            'customer[lastName]' => 'Something New',
            'customer[street]' => 'Something New',
            'customer[zipcode]' => 'Something New',
            'customer[city]' => 'Something New',
            'customer[country]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/customer/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getFirstName());
        self::assertSame('Something New', $fixture[0]->getLastName());
        self::assertSame('Something New', $fixture[0]->getStreet());
        self::assertSame('Something New', $fixture[0]->getZipcode());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getCountry());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Customer();
        $fixture->setEmail('Value');
        $fixture->setPassword('Value');
        $fixture->setFirstName('Value');
        $fixture->setLastName('Value');
        $fixture->setStreet('Value');
        $fixture->setZipcode('Value');
        $fixture->setCity('Value');
        $fixture->setCountry('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/customer/');
        self::assertSame(0, $this->repository->count([]));
    }
}
