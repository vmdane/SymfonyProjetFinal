<?php

namespace App\Tests\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class NotificationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $notificationRepository;
    private string $path = '/notification/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->notificationRepository = $this->manager->getRepository(Notification::class);

        foreach ($this->notificationRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Notification index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'notification[content]' => 'Testing',
            'notification[sendDate]' => 'Testing',
            'notification[isRead]' => 'Testing',
            'notification[type]' => 'Testing',
            'notification[user]' => 'Testing',
            'notification[book]' => 'Testing',
            'notification[category]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->notificationRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Notification();
        $fixture->setContent('My Title');
        $fixture->setSendDate('My Title');
        $fixture->setIsRead('My Title');
        $fixture->setType('My Title');
        $fixture->setUser('My Title');
        $fixture->setBook('My Title');
        $fixture->setCategory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Notification');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Notification();
        $fixture->setContent('Value');
        $fixture->setSendDate('Value');
        $fixture->setIsRead('Value');
        $fixture->setType('Value');
        $fixture->setUser('Value');
        $fixture->setBook('Value');
        $fixture->setCategory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'notification[content]' => 'Something New',
            'notification[sendDate]' => 'Something New',
            'notification[isRead]' => 'Something New',
            'notification[type]' => 'Something New',
            'notification[user]' => 'Something New',
            'notification[book]' => 'Something New',
            'notification[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/notification/');

        $fixture = $this->notificationRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getSendDate());
        self::assertSame('Something New', $fixture[0]->getIsRead());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getBook());
        self::assertSame('Something New', $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Notification();
        $fixture->setContent('Value');
        $fixture->setSendDate('Value');
        $fixture->setIsRead('Value');
        $fixture->setType('Value');
        $fixture->setUser('Value');
        $fixture->setBook('Value');
        $fixture->setCategory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/notification/');
        self::assertSame(0, $this->notificationRepository->count([]));
    }
}
