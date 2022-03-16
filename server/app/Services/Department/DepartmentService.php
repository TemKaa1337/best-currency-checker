<?php

namespace App\Services\Department;

use App\Models\Department;
use App\Services\Distance\{DistanceCalculator, Point};

class DepartmentService
{
    private string $coordinates;
    private int $radiusInMeters;
    private int $limit;

    public function __construct(string $coordinates, int $radiusInMeters, int $limit)
    {
        $this->coordinates = $coordinates;
        $this->radiusInMeters = $radiusInMeters;
        $this->limit = $limit;
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

        return $departmentInfo;
    }
}

?>
