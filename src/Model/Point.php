<?php

namespace Aerofiles\Model;

use Location\Coordinate;

class Point
{
    /**
     * @var \DateTimeImmutable
     */
    private $time;

    /**
     * @var int Altitude in meters
     */
    private $altitude;

    /**
     * @var Coordinate
     */
    private $coordinate;

    /**
     * Point constructor.
     * @param \DateTimeImmutable $time
     * @param int $altitude
     * @param Coordinate $coordinate
     */
    public function __construct(\DateTimeImmutable $time, int $altitude, Coordinate $coordinate)
    {
        $this->time = $time;
        $this->altitude = $altitude;
        $this->coordinate = $coordinate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getAltitude(): int
    {
        return $this->altitude;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}
