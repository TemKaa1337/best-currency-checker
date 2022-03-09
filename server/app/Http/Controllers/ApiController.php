<?php

namespace App\Http\Controllers;

use App\Services\Department\DepartmentService;
use Illuminate\Http\{Request, Response};

class ApiController extends Controller
{
    public function getNearestDepartments(Request $request): Response
    {
        // TODO: add validation for , and .
        $request->validate([
            'location' => ['required', 'max:20'],
            'radius' => ['required', 'min:4', 'max:5']
        ]);

        $departmentService = new DepartmentService(
            location: $request->location,
            radiusInMeters: $request->radius
        );
        $departments = $departmentService->getNearestDepartmentsWithBestRates();

        return response($departments, 200);
    }
}
