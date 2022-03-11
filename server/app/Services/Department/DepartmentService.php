<?php

namespace App\Services\Department;

use App\Models\Department;
use App\Services\Distance\{DistanceCalculator, Point};

class DepartmentService
{
    private string $coordinates;
    private int $radiusInMeters;

    public function __construct(string $coordinates, int $radiusInMeters)
    {
        $this->coordinates = $coordinates;
        $this->radiusInMeters = $radiusInMeters;
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
            ->take(5)
            ->values()
            ->all();

        return $departmentInfo;
    }
}

?>
