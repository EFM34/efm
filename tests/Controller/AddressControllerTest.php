<?php

namespace App\Tests\Controller;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AddressControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $addressRepository;
    private string $path = '/address/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->addressRepository = $this->manager->getRepository(Address::class);

        foreach ($this->addressRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'address[name]' => 'Testing',
            'address[client_name]' => 'Testing',
            'address[street]' => 'Testing',
            'address[postal_code]' => 'Testing',
            'address[city]' => 'Testing',
            'address[state]' => 'Testing',
            'address[more_details]' => 'Testing',
            'address[updated_at]' => 'Testing',
            'address[created_at]' => 'Testing',
            'address[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->addressRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setName('My Title');
        $fixture->setClient_name('My Title');
        $fixture->setStreet('My Title');
        $fixture->setPostal_code('My Title');
        $fixture->setCity('My Title');
        $fixture->setState('My Title');
        $fixture->setMore_details('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setName('Value');
        $fixture->setClient_name('Value');
        $fixture->setStreet('Value');
        $fixture->setPostal_code('Value');
        $fixture->setCity('Value');
        $fixture->setState('Value');
        $fixture->setMore_details('Value');
        $fixture->setUpdated_at('Value');
        $fixture->setCreated_at('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'address[name]' => 'Something New',
            'address[client_name]' => 'Something New',
            'address[street]' => 'Something New',
            'address[postal_code]' => 'Something New',
            'address[city]' => 'Something New',
            'address[state]' => 'Something New',
            'address[more_details]' => 'Something New',
            'address[updated_at]' => 'Something New',
            'address[created_at]' => 'Something New',
            'address[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/address/');

        $fixture = $this->addressRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getClient_name());
        self::assertSame('Something New', $fixture[0]->getStreet());
        self::assertSame('Something New', $fixture[0]->getPostal_code());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getState());
        self::assertSame('Something New', $fixture[0]->getMore_details());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setName('Value');
        $fixture->setClient_name('Value');
        $fixture->setStreet('Value');
        $fixture->setPostal_code('Value');
        $fixture->setCity('Value');
        $fixture->setState('Value');
        $fixture->setMore_details('Value');
        $fixture->setUpdated_at('Value');
        $fixture->setCreated_at('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/address/');
        self::assertSame(0, $this->addressRepository->count([]));
    }
}
