<?php

namespace Aerofiles;

use Aerofiles\Exception\InvalidStreamException;

trait StreamTrait {

    /**
     * @param $stream
     * @throws InvalidStreamException
     */
    private function checkStream($stream)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new InvalidStreamException();
        }
    }
}
