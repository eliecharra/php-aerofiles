<?php

namespace Aerofiles\Model;

class Flight
{

    /**
     * @var TakeOff
     */
    private $takeOff;

    /**
     * @var Landing
     */
    private $landing;

    /**
     * @var Point[]
     */
    private $track;

    /**
     * Track constructor.
     * @param TakeOff $takeOff
     * @param Landing $landing
     * @param Point[] $track
     */
    public function __construct(
        TakeOff $takeOff,
        Landing $landing,
        array $track = []
    )
    {
        $this->takeOff = $takeOff;
        $this->landing = $landing;
        $this->track = $track;
    }

    /**
     * @return TakeOff
     */
    public function getTakeOff(): TakeOff
    {
        return $this->takeOff;
    }

    /**
     * @return Landing
     */
    public function getLanding(): Landing
    {
        return $this->landing;
    }

    /**
     * @return Point[]
     */
    public function getTrack(): array
    {
        return $this->track;
    }

}
