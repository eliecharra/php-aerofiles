<?php

namespace Aerofiles\Tests\IGC;

use Aerofiles\IGC\Reader;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{

    /**
     * @var Reader
     */
    private $reader;

    public function setUp()
    {
        $this->reader = new Reader();
    }

    public function tearDown()
    {
        $this->reader = null;
    }

    /**
     * @expectedException Aerofiles\Exception\InvalidStreamException
     */
    public function testInvalidStream()
    {
        $this->reader->read('foobar');
    }

    /**
     * @dataProvider igcProvider
     */
    public function testRead($file)
    {
        $stream = fopen(__DIR__ . '/data/' . $file, 'rb');
        $result = $this->reader->read($stream);
        $this->assertEquals('151218', $result->getDate()->format('dmy'));
        $this->assertEquals('Saint Hilaire', $result->getFlight()->getTakeOff()->getSite());
        $this->assertEquals(933, $result->getFlight()->getTakeOff()->getPoint()->getGpsAltitude());
        $this->assertEquals(893, $result->getFlight()->getTakeOff()->getPoint()->getPressureAltitude());
        $this->assertEquals(45.306833, $result->getFlight()->getTakeOff()->getPoint()->getCoordinate()->getLat());
        $this->assertEquals(5.887717, $result->getFlight()->getTakeOff()->getPoint()->getCoordinate()->getLng());
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('dmy H:i:s O', '151218 13:16:18 +0000'),
            $result->getFlight()->getTakeOff()->getPoint()->getTime()
        );

        $this->assertEquals('', $result->getFlight()->getLanding()->getSite());
        $this->assertEquals(314, $result->getFlight()->getLanding()->getPoint()->getGpsAltitude());
        $this->assertEquals(171, $result->getFlight()->getLanding()->getPoint()->getPressureAltitude());
        $this->assertEquals(45.302533, $result->getFlight()->getLanding()->getPoint()->getCoordinate()->getLat());
        $this->assertEquals(5.906600, $result->getFlight()->getLanding()->getPoint()->getCoordinate()->getLng());
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('dmy H:i:s O', '151218 13:37:23 +0000'),
            $result->getFlight()->getLanding()->getPoint()->getTime()
        );

        $this->assertEquals('Elie CHARRA', $result->getPilot());
        $this->assertEquals('ADVANCE Alpha 6', $result->getGliderType());

        $this->assertCount(1266, $result->getFlight()->getTrack());
    }

    public function igcProvider()
    {
        return [
            ['test.igc'],
        ];
    }

    /**
     * @expectedException Aerofiles\Exception\IGC\InvalidLineException
     */
    public function testInvalidLine()
    {
        $stream = fopen('data://text/plain,ZXCTd4d6a56e3e175af5', 'rb');
        $this->reader->read($stream);
    }

    /**
     * @expectedException Aerofiles\Exception\IGC\InvalidLineLengthException
     */
    public function testInvalidLineLength()
    {
        $stream = fopen('data://text/plain,AXCTd4d6a56e3e175af5xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'rb');
        $this->reader->read($stream);
    }

    /**
     * @expectedException Aerofiles\Exception\MissingRequiredFieldException
     */
    public function testMissingDate()
    {
        $stream = fopen(__DIR__ . '/data/missing_date.igc', 'rb');
        $this->reader->read($stream);
    }

    /**
     */
    public function testNewDate()
    {
        $stream = fopen(__DIR__ . '/data/test_new_day.igc', 'rb');
        $result = $this->reader->read($stream);

        $this->assertEquals('151218', $result->getDate()->format('dmy'));
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('dmy H:i:s O', '151218 23:16:18 +0000'),
            $result->getFlight()->getTakeOff()->getPoint()->getTime()
        );
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('dmy H:i:s O', '161218 00:16:20 +0000'),
            $result->getFlight()->getLanding()->getPoint()->getTime()
        );
    }
}
