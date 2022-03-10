<?php

namespace App\Http\Controllers;

use App\Services\Department\DepartmentService;
use Illuminate\Http\{Request, Response};
use App\Http\Requests\ApiRequest;
use App\Exceptions\ValidationException;

class ApiController extends Controller
{
    public function getNearestDepartments(ApiRequest $request): Response
    {
        // TODO: add validation for , and .
        $request->validate();

        // try {
        //     $request->validateFields();
        // } catch (ValidationException $e) {
        //     return response($e->getMessage(), 400);
        // }

        $departmentService = new DepartmentService(
            location: $request->location,
            radiusInMeters: $request->radius
        );
        $departments = $departmentService->getNearestDepartmentsWithBestRates();

        return response($departments, 200);
    }
}
