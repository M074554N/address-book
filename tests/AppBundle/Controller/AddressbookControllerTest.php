<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\AddressBook\Entry;
use AppBundle\Repository\AddressBook\EntryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressbookControllerTest extends WebTestCase
{

	private $client;

	private $objectManager;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->client = static::createClient();
		$this->objectManager = $this->createMock(ObjectManager::class);
	}

	public function testAll()
    {
        $crawler = $this->client->request('GET', '/all');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('All Contacts', $crawler->filter('.card-header')->text());
    }

    public function testCreateNewEntry()
	{

		$entry = new Entry();
		$entry->setFirstname('Mohamed');
		$entry->setLastname('Hassan');
		$entry->setPhone('00201270605965');
		$entry->setEmail('hassan.mohamed.sf@gmail.com');
		$entry->setStreet('17 street');
		$entry->setZip('11571');
		$entry->setCity('Cairo');
		$entry->setCountry('EG');
		$entry->setBirthday(new \DateTime('-30 years'));

		$entryRepo = $this->createMock(EntryRepository::class);
		$entryRepo->expects($this->any())
			->method('find')
			->willReturn($entry);

		$this->assertEquals('Mohamed', $entryRepo->find(123)->getFirstName());
		$this->assertEquals('Hassan', $entryRepo->find(123)->getLastName());
		$this->assertEquals('00201270605965', $entryRepo->find(123)->getPhone());
		$this->assertGreaterThan($entryRepo->find(123)->getBirthday(), new \DateTime('now'));
	}
}
