<?php

namespace App\Services\Department;

use App\Models\BankCurrencyInfo;
use App\Services\Distance\{DistanceService, Point};

class DepartmentService
{
    private string $location;
    private int $radiusInMeters;

    public function __construct(string $location, int $radiusInMeters)
    {
        $this->location = $location;
        $this->radiusInMeters = $radiusInMeters;
    }

    public function getNearestDepartmentsWithBestRates(): array
    {
        $userLocationPoint = new Point(explode(',', $this->location));

        // TODO: unset distance
        // TODO: add is_working_now
        $departmentInfo = BankCurrencyInfo::all()
                                            ->map(function (BankCurrencyInfo $info) use ($userLocationPoint) : BankCurrencyInfo {
                                                $departmentLocationPoint = new Point($info->coordinates);
                                                $distanceService = new DistanceService(
                                                    startPoint: $userLocationPoint,
                                                    endPoint: $departmentLocationPoint
                                                );

                                                $info->distance = $distanceService->getDistanceBetweenPointsInMeters();

                                                return $info;
                                            })->reject(fn (BankCurrencyInfo $info): bool => $info->distance > $this->radiusInMeters)
                                            ->sortBy(fn (BankCurrencyInfo $info): float => $info->distance)
                                            ->values()
                                            ->all();

        return $departmentInfo;
    }
}

?>
