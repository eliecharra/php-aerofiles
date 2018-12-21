<?php

namespace Aerofiles;

interface ReaderInterface {
    public function read($stream) : ?ResultInterface;
}
