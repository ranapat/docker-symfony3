<?php

namespace NeoBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;

use NeoBundle\Entity\Neo;

class NeoTest extends TestCase
{
    public function testApproachAt()
    {
        $neo = new Neo();
        $initial = new \DateTime();
        $initial->setTime(0, 0, 0, 0);
        $neo->setApproachAt($initial);
        $this->assertEquals($initial, $neo->getApproachAt());

        $initialAsString = $initial->format('Y-m-d');
        $neo->setApproachAtAsString($initialAsString);
        $this->assertEquals($initial, $neo->getApproachAt());
    }
}