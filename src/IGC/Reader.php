<?php

namespace Aerofiles\IGC;

use Aerofiles\Exception\IGC\InvalidLineException;
use Aerofiles\Exception\IGC\InvalidLineLengthException;
use Aerofiles\Exception\InvalidStreamException;
use Aerofiles\Exception\MissingRequiredFieldException;
use Aerofiles\Model\Flight;
use Aerofiles\Model\Landing;
use Aerofiles\Model\Point;
use Aerofiles\Model\TakeOff;
use Aerofiles\ReaderInterface;
use Aerofiles\ResultInterface;
use Aerofiles\StreamTrait;
use Location\Coordinate;


class Reader implements ReaderInterface {

    use StreamTrait;

    /**
     * @var array
     */
    private $trackData = [
        'date' => null,
        'pilot' => '',
        'site' => '',
        'gliderType' => '',
    ];

    /**
     * @var string
     */
    private $previousTime;

    /**
     * @param resource $stream
     * @throws InvalidLineException
     * @throws InvalidLineLengthException
     * @throws InvalidStreamException
     * @throws MissingRequiredFieldException
     */
    public function read($stream) : ?ResultInterface
    {
        $this->checkStream($stream);

        $this->trackData['points'] = [];

        while(!feof($stream)) {
            $line = trim(fgets($stream));
            $this->parseLine($line);
        }

        if (count($this->trackData['points']) === 0) {
            return null;
        }

        return new Result(
            $this->trackData['date'],
            $this->trackData['pilot'],
            $this->trackData['gliderType'],
            new Flight(
                new TakeOff(
                    $this->trackData['site'],
                    $this->trackData['points'][0]
                ),
                new Landing(
                    '',
                    $this->trackData['points'][count($this->trackData['points']) - 1]
                ),
                $this->trackData['points']
            )
        );
    }

    private function parseLine(string $line) : void
    {
        if (strlen($line) < 1) {
            return;
        }

        if (strlen($line) > 76) {
            throw new InvalidLineLengthException();
        }

        if (!in_array($line[0], ['A','B','C','D','E','F','G','H','I','J','K','L','M'])) {
            throw new InvalidLineException();
        }

        $method = "parse$line[0]Record";
        $this->$method($line);
    }

    private function parseARecord(string $line){}

    private function parseBRecord(string $line){
        if (preg_match(
            '/B
                (?P<date>\d{2}\d{2}\d{2})
                (?P<latDeg>\d{2})(?P<latMin>\d{2})(?P<latSec>\d{3})(?P<latHemi>\w)
                (?P<lonDeg>\d{3})(?P<lonMin>\d{2})(?P<lonSec>\d{3})(?P<lonHemi>\w).
                (?P<elevP>\d{5}|(-\d{4}))(?P<elevG>\d{5})
            /x', $line, $m)
        ) {
            /** @var \DateTimeImmutable $date */
            $date = $this->trackData['date'];
            $time = \DateTimeImmutable::createFromFormat('dmyHisO', $date->format('dmy') . $m['date'] . '+0000');

            if (!$time instanceof \DateTimeImmutable) {
                throw new InvalidLineException();
            }

            if(null !== $this->previousTime && $time < $this->previousTime) {
                $time = $time->add(new \DateInterval('P1D'));
            }

            $lat = (strtoupper($m['latHemi']) == 'N'? 1 : -1) *
                ($m['latDeg'] + ($m['latMin'] * 1000 + $m['latSec']) / 1000.0 / 60);
            $lon = (strtoupper($m['lonHemi']) == 'E'? 1 : -1) *
                ($m['lonDeg'] + ($m['lonMin'] * 1000 + $m['lonSec']) / 1000.0 / 60);
            $coordinate = new Coordinate(round($lat, 6), round($lon, 6));
            $altitude = (int)$m['elevG'];

            $this->trackData['points'][] = new Point($time, $altitude, $coordinate, (int)$m['elevP']);
            $this->previousTime = $time;
        }
    }
    private function parseCRecord(string $line){}
    private function parseDRecord(string $line){}
    private function parseERecord(string $line){}
    private function parseFRecord(string $line){}
    private function parseGRecord(string $line){}

    private function parseHRecord(string $line){

        if (preg_match('/PILOT.*?:(.*)$/mi', $line, $m)) {
            $this->trackData['pilot'] = trim($m[1]);
        }

        if (preg_match('/DTEDATE:(?P<date>\d{2}\d{2}\d{2})/', $line, $m)) {
            $date = \DateTimeImmutable::createFromFormat('dmy', $m['date']);
            if (!$date instanceof \DateTimeImmutable) {
                throw new InvalidLineException();
            }
            $this->trackData['date'] = $date;
        }

        if (preg_match('/GLIDERTYPE.*?:(.*)$/mi', $line, $m)) {
            $this->trackData['gliderType'] = trim($m[1]);
        }

        if (preg_match('/H.SIT.*?:(.*)$/mi', $line, $m)) {
            $this->trackData['site'] = trim($m[1]);
        }
    }

    private function parseIRecord(string $line){
        if (!$this->trackData['date'] instanceof \DateTimeImmutable) {
            throw new MissingRequiredFieldException();
        }
    }
    private function parseJRecord(string $line){}
    private function parseKRecord(string $line){}
    private function parseLRecord(string $line){}
    private function parseMRecord(string $line){}
}
