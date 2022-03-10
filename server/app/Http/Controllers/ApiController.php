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
            location: $request->location,
            radiusInMeters: $request->radius
        );
        $departments = $departmentService->getNearestDepartmentsWithBestRates();

        return response($departments, 200);
    }
}
