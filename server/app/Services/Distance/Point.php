<?php

namespace App\Services\Distance;

class Point
{
    private float $x;
    private float $y;

    public function __construct(array $point)
    {
        $this->x = (float) $point[0];
        $this->y = (float) $point[1];
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}

?>
