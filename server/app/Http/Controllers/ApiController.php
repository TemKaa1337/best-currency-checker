<?php

namespace App\Http\Controllers;

use App\Services\Department\DepartmentService;
use App\Http\Requests\ApiRequest;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function getNearestDepartments(ApiRequest $request): Response
    {
        $departmentService = new DepartmentService(
            coordinates: $request->location,
            radiusInMeters: $request->radius,
            limit: $request->limit,
            currency: $request->currency,
            operationType: $request->operationType
        );
        $departments = $departmentService->getNearestDepartmentsWithBestRates();

        return response($departments, 200);
    }
}
