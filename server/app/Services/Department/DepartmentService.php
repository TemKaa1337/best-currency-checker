<?php

namespace App\Services\Department;

use App\Models\Department;
use App\Services\Distance\{DistanceCalculator, Point};

class DepartmentService
{
    private string $coordinates;
    private int $radiusInMeters;
    private int $limit;
    private string $currency;
    private string $operationType;

    public function __construct(
        string $coordinates,
        int $radiusInMeters,
        int $limit,
        string $currency,
        string $operationType
    )
    {
        $this->coordinates = $coordinates;
        $this->radiusInMeters = $radiusInMeters;
        $this->limit = $limit;
        $this->currency = $currency;
        $this->operationType = $operationType;
    }

    public function getNearestDepartmentsWithBestRates(): array
    {
        $userLocationPoint = new Point(explode(',', $this->coordinates));

        // TODO: add is_working_now
        $departmentInfo = Department::all()
            ->map(function (Department $department) use ($userLocationPoint) : Department {
                $departmentLocationPoint = new Point($department->coordinates);
                $calculator = new DistanceCalculator(
                    startPoint: $userLocationPoint,
                    endPoint: $departmentLocationPoint
                );

                $department->distance = $calculator->getDistanceBetweenPointsInMeters();

                return $department;
            })->reject(fn (Department $department): bool => $department->distance > $this->radiusInMeters)
            ->sortBy(fn (Department $department): int => $department->distance)
            ->take($this->limit)
            ->values()
            ->all();

        $a = $b;

        return $departmentInfo;
    }
}

?>
