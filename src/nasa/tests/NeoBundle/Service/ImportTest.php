<?php

namespace NeoBundle\Tests\Service;

use PHPUnit\Framework\TestCase;

use Doctrine\ORM\EntityManager;

use Buzz\Browser;

use NeoBundle\Repository\NeoRepository;
use NeoBundle\Entity\Neo;
use NeoBundle\Service\Import;
use NeoBundle\Exception\NeoImportStructureInvalidException;

class ImportTest extends TestCase
{
    public function testImportUrlGeneration()
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $browserMock = $this->createMock(Browser::class);
        $getMock = $this->createMock(MockClass::class);
        $neoRepositoryMock = $this->createMock(NeoRepository::class);
        $key = 'key';
        $url = 'key=##key##&start=##start##&end=##end##';
        $days = 3;

        $browserMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('key=key&start=2017-08-26&end=2017-08-28'))
            ->willReturn($getMock);
        $getMock->method('getContent')->willReturn('{ "near_earth_objects": [] }');

        $import = new Import(
            $entityManagerMock,
            $browserMock,
            $neoRepositoryMock,
            $key, $url, $days
        );

        $import->import();

        $this->assertTrue(true);
    }

    /**
     * @expectedException NeoBundle\Exception\NeoImportStructureInvalidException
     */
    public function testImportResponseException()
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $browserMock = $this->createMock(Browser::class);
        $getMock = $this->createMock(MockClass::class);
        $neoRepositoryMock = $this->createMock(NeoRepository::class);
        $key = 'key';
        $url = 'key=##key##&start=##start##&end=##end##';
        $days = 3;

        $browserMock
            ->method('get')
            ->willReturn($getMock);
        $getMock->method('getContent')->willReturn('{}');

        $import = new Import(
            $entityManagerMock,
            $browserMock,
            $neoRepositoryMock,
            $key, $url, $days
        );

        $import->import();
    }

    public function testImportPersistance()
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $browserMock = $this->createMock(Browser::class);
        $getMock = $this->createMock(MockClass::class);
        $neoRepositoryMock = $this->createMock(NeoRepository::class);
        $key = 'key';
        $url = 'key=##key##&start=##start##&end=##end##';
        $days = 3;

        $neo = new Neo();
        $neo
            ->setName('name')
            ->setReference(123456)
            ->setSpeed(123.456)
            ->setIsHazardous(false)
            ->setApproachAtAsString('2017-08-27');

        $browserMock
            ->method('get')
            ->willReturn($getMock);
        $getMock
            ->method('getContent')
            ->willReturn('{ "near_earth_objects": { "2017-08-27": [ { "name": "name", "neo_reference_id": 123456, "is_potentially_hazardous_asteroid": false, "close_approach_data": [ { "close_approach_date": "2017-08-27", "relative_velocity": { "kilometers_per_hour": 123.456 } } ]  } ] } }');
        $neoRepositoryMock
            ->expects($this->exactly(1))
            ->method('referenceExists')
            ->with($this->equalTo(123456))
            ->willReturn(true);
        $entityManagerMock
            ->expects($this->exactly(1))
            ->method('persist')
            ->with($this->equalTo($neo));
        $entityManagerMock
            ->expects($this->exactly(1))
            ->method('flush');

        $import = new Import(
            $entityManagerMock,
            $browserMock,
            $neoRepositoryMock,
            $key, $url, $days
        );

        $import->import();

        $this->assertTrue(true);
    }
}

class MockClass {
    public function getContent() {}
}
