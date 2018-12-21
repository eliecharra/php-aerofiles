<?php

namespace Aerofiles\Model;

class TakeOff
{
    /**
     * @var string
     */
    private $site;

    /**
     * @var Point
     */
    private $point;

    /**
     * TakeOff constructor.
     * @param string $site
     * @param Point $point
     */
    public function __construct(string $site, Point $point)
    {
        $this->site = $site;
        $this->point = $point;
    }

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * @return Point
     */
    public function getPoint(): Point
    {
        return $this->point;
    }
}
