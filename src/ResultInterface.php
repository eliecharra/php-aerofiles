<?php

namespace Aerofiles;

use Aerofiles\Model\Flight;

interface ResultInterface
{
    public function getDate() : \DateTimeImmutable;
    public function getFlight() : Flight;
}
