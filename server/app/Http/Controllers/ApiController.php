<?php

namespace App\Http\Controllers;

use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function getNearestDepartments(Request $request): Response
    {
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
