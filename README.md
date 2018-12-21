# php-aerofile

[![Build Status](https://travis-ci.com/eliecharra/php-aerofiles.svg?branch=master)](https://travis-ci.com/eliecharra/php-aerofiles)
![Code coverage](https://scrutinizer-ci.com/g/eliecharra/php-aerofiles/badges/coverage.png?b=master)

**waypoint, task, tracklog readers and writers for aviation**

## Supported formats :

- IGC

## Usage :

```php
<?php
        use Aerofiles\IGC\Reader;

        $stream = fopen($file, 'rb');
        $reader = new Reader();
        $result = $reader->read($stream);
        
        // Retrieve takeoff point info
        $result->getFlight()->getTakeOff()->getSite(); // Saint Hilaire
        $result->getFlight()->getTakeOff()->getPoint()->getAltitude(); // 933
        $result->getFlight()->getTakeOff()->getPoint()->getCoordinate()->getLat(); // 45.306833
        $result->getFlight()->getTakeOff()->getPoint()->getCoordinate()->getLng(); // 5.887717
        $result->getFlight()->getTakeOff()->getPoint()->getTime()->format('H:i:s'); // 13:16:18
        
        // Retrieve landing point info
        $result->getFlight()->getLanding()->getSite();
        $result->getFlight()->getLanding()->getPoint()->getAltitude();
        $result->getFlight()->getLanding()->getPoint()->getCoordinate()->getLat();
        $result->getFlight()->getLanding()->getPoint()->getCoordinate()->getLng();
        $result->getFlight()->getLanding()->getPoint()->getTime()->format('H:i:s');

        // Read IGC header metadata
        $result->getPilot(); // Elie CHARRA
        $result->getGliderType(); // ADVANCE ALpha 6
        
        // Retrieve a list of track points
        $result->getFlight()->getTrack(); // Point[]
```
