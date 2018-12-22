<?php

namespace Aerofiles\IGC;

use Aerofiles\Model\Flight;
use Aerofiles\ResultInterface;

class Result implements ResultInterface
{

    /**
     * @var \DateTimeImmutable date of the flight (does not contains time)
     */
    private $date;

    /**
     * @var string Pilot name
     */
    private $pilot;

    /**
     * @var string Paraglider type
     */
    private $gliderType;

    /**
     * @var Flight
     */
    private $flight;

    /**
     * Result constructor.
     *
     * @param string $pilot
     * @param string $gliderType
     */
    public function __construct(
        \DateTimeImmutable $date,
        string $pilot,
        string $gliderType,
        Flight $flight
    ) {
        $this->date = $date;
        $this->pilot = $pilot;
        $this->gliderType = $gliderType;
        $this->flight = $flight;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getPilot(): string
    {
        return $this->pilot;
    }

    /**
     * @return string
     */
    public function getGliderType(): string
    {
        return $this->gliderType;
    }

    /**
     * @inheritdoc
     */
    public function getFlight(): Flight
    {
        return $this->flight;
    }
}
