<?php

namespace Aerofiles;

use Aerofiles\Model\Flight;

interface ResultInterface
{
    public function getFlight() : Flight;
}
