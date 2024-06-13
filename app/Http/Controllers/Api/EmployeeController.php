<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employee::all();
            return EmployeeResource::collection($employees);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch employees.', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:100',
            'position' => 'required|string',
            'gender' => 'required|in:male,female',
            'employment_period' => 'required|string',
            'active' => 'required|boolean',
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        [$startDate, $endDate] = explode(' - ', $request->employment_period);

        $employee = new Employee();
        $employee->employee_name = $request->employee_name;
        $employee->position = $request->position;
        $employee->gender = $request->gender;
        $employee->employment_start_date = $startDate;
        $employee->employment_end_date = $endDate;
        $employee->active_status = $request->active ? '1' : '0';

        if ($request->hasFile('photo')) {
            $photoPaths = [];
            foreach ($request->file('photo') as $file) {
                $photoName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/photos', $photoName);
                $photoPaths[] = $photoName;
            }
            $employee->photo = $photoPaths[0];
        }

        $employee->save();

        return response()->json([
            'message' => 'Employee created successfully.',
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_name' => 'required|string|max:100',
            'position' => 'required|string',
            'gender' => 'required|in:male,female',
            'employment_period' => 'nullable|string',
            'active_status' => 'required|in:0,1',
        ]);

        $employmentPeriod = $request->input('employment_period');
        $dates = explode(' - ', $employmentPeriod);
        $startDate = Carbon::parse($dates[0]);
        $endDate = Carbon::parse($dates[1]);

        $employee->employee_name = $request->input('employee_name');
        $employee->position = $request->input('position');
        $employee->gender = $request->input('gender');
        $employee->employment_start_date = $startDate;
        $employee->employment_end_date = $endDate;
        $employee->active_status = $request->input('active_status');

        $employee->save();

        return response()->json([
            'message' => 'Employee updated successfully.',
            'redirect' => url('/employee')
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully.',
        ]);
    }
    public function show($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return new EmployeeResource($employee);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Employee not found.', 'error' => $e->getMessage()], 404);
        }
    }
}
