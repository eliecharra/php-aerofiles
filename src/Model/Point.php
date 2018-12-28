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
     * @var int Altitude in meters (retrieved from gps)
     */
    private $gpsAltitude;

    /**
     * @var int Altitude in meters (retrieved from pressure sensor)
     */
    private $pressureAltitude;

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
    public function __construct(\DateTimeImmutable $time, int $GPSAltitude, Coordinate $coordinate, int $pressureAltitude = null)
    {
        $this->time = $time;
        $this->gpsAltitude = $GPSAltitude;
        $this->pressureAltitude = $pressureAltitude;
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
     * @deprecated
     * @return int
     */
    public function getAltitude(): int
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated, use either getGpsAltitude or getPressureAltitude', E_USER_DEPRECATED);
        return $this->getGpsAltitude();
    }

    /**
     * @return int
     */
    public function getGpsAltitude(): int
    {
        return $this->gpsAltitude;
    }

    /**
     * @return int|null
     */
    public function getPressureAltitude(): ?int
    {
        return $this->pressureAltitude;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }
}
