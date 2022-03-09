<?php

namespace App\Services\Distance;

use App\Services\Distance\Point;

class DistanceService
{
    private const EARTH_RADIUS = 6371000;

    private Point $startPoint;
    private Point $endPoint;

    public function __construct(Point $startPoint, Point $endPoint)
    {
        $this->startPoint = $startPoint;
        $this->endPoint = $endPoint;
    }

    public function getDistanceBetweenPointsInMeters(): int
    {
        $latStart = deg2rad($this->startPoint->getX());
        $lonStart = deg2rad($this->startPoint->getY());
        $latEnd = deg2rad($this->endPoint->getX());
        $lonEnd = deg2rad($this->endPoint->getY());

        $lonDelta = $lonEnd - $lonStart;
        $a = pow(cos($latEnd) * sin($lonDelta), 2) +
            pow(cos($latStart) * sin($latEnd) - sin($latStart) * cos($latEnd) * cos($lonDelta), 2);
        $b = sin($latStart) * sin($latEnd) + cos($latStart) * cos($latEnd) * cos($lonDelta);

        return (float) (atan2(sqrt($a), $b) * self::EARTH_RADIUS);
    }
}

?>
