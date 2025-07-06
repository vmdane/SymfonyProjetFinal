<?php

namespace App\Tests\Controller;

use App\Entity\Bookshelf;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BookshelfControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $bookshelfRepository;
    private string $path = '/bookshelf/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->bookshelfRepository = $this->manager->getRepository(Bookshelf::class);

        foreach ($this->bookshelfRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Bookshelf index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'bookshelf[name]' => 'Testing',
            'bookshelf[user]' => 'Testing',
            'bookshelf[books]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->bookshelfRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bookshelf();
        $fixture->setName('My Title');
        $fixture->setUser('My Title');
        $fixture->setBooks('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Bookshelf');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bookshelf();
        $fixture->setName('Value');
        $fixture->setUser('Value');
        $fixture->setBooks('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'bookshelf[name]' => 'Something New',
            'bookshelf[user]' => 'Something New',
            'bookshelf[books]' => 'Something New',
        ]);

        self::assertResponseRedirects('/bookshelf/');

        $fixture = $this->bookshelfRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getBooks());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Bookshelf();
        $fixture->setName('Value');
        $fixture->setUser('Value');
        $fixture->setBooks('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/bookshelf/');
        self::assertSame(0, $this->bookshelfRepository->count([]));
    }
}
