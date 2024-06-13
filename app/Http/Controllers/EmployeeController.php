<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $title = 'Data Karyawan';
        $employees = Employee::all();

        // Menghitung employment_period
        foreach ($employees as $employee) {
            if ($employee->employment_start_date && $employee->employment_end_date) {
                $startDate = Carbon::parse($employee->employment_start_date);
                $endDate = Carbon::parse($employee->employment_end_date);
                $employee->employment_period = $startDate->diffInMonths($endDate);
            } else {
                $employee->employment_period = 'Data Tidak Lengkap';
            }
        }

        return view('admin.employees.index', compact('title', 'employees'));
    }
    public function create()
    {
        $title = "Tambah Data Karyawan";
        return view('admin.employees.create', [
            'title' => $title
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:100',
            'position' => 'required|string',
            'gender' => 'required|in:male,female',
            'employment_period' => 'required|string',
            'active' => 'required|boolean', // pastikan validasi boolean
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Pemisahan rentang tanggal
        [$startDate, $endDate] = explode(' - ', $request->employment_period);

        // Simpan data
        $employee = new Employee();
        $employee->employee_name = $request->employee_name;
        $employee->position = $request->position;
        $employee->gender = $request->gender;
        $employee->employment_start_date = $startDate;
        $employee->employment_end_date = $endDate;
        $employee->active_status = $request->active ? '1' : '0'; // konversi ke string '1' atau '0' sesuai boolean

        // Proses upload foto
        if ($request->hasFile('photo')) {
            $photoPaths = [];
            foreach ($request->file('photo') as $file) {
                $photoName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/photos', $photoName);
                $photoPaths[] = $photoName;
            }
            // Simpan hanya nama file pertama (misalnya, jika hanya satu foto yang diunggah)
            $employee->photo = $photoPaths[0]; // Menggunakan indeks pertama dari array
        }

        $employee->save();

        return redirect('/employee')->with('success', 'Employee created successfully.');
    }





    public function edit(Employee $employee)
    {
        $title = "Edit Data Karyawan";
        return view('admin.employees.edit', compact('title', 'employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Validasi data input
        $request->validate([
            'employee_name' => 'required|string|max:100',
            'position' => 'required|string',
            'gender' => 'required|in:male,female',
            'employment_period' => 'nullable|string',
            'active_status' => 'required|in:0,1',
        ]);

        // Pisahkan tanggal awal dan akhir dari employment_period
        $employmentPeriod = $request->input('employment_period');
        $dates = explode(' - ', $employmentPeriod);
        $startDate = Carbon::parse($dates[0]);
        $endDate = Carbon::parse($dates[1]);

        // Update data karyawan
        $employee->employee_name = $request->input('employee_name');
        $employee->position = $request->input('position');
        $employee->gender = $request->input('gender');
        $employee->employment_start_date = $startDate;
        $employee->employment_end_date = $endDate;
        $employee->active_status = $request->input('active_status');

        // Simpan perubahan
        $employee->save();

        // Redirect dengan pesan sukses
        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'Data karyawan berhasil dihapus.');
    }
    public function upload(Request $request)
    {
        // Validation
        $request->validate([
            'file' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/photos', $fileName); // Store in storage/photos

            return response()->json(['success' => $fileName], 200);
        }

        return response()->json(['error' => 'Invalid file'], 400);
    }

    // Additional methods like show() can be added if needed
}
