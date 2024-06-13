@extends("layouts.app")

@push("stylesheet")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endpush

@section("body")
    <a href="{{ route("employee.create") }}" class="btn btn-primary">Tambah Karyawan</a>

    {{-- datatables --}}

    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <div class="m-0 font-weight-bold text-primary">
                Example data karyawan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" id="dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Jenis Kelamin</th>
                            <th>Masa Kerja (bulan)</th>
                            <th>Status</th>
                            <th>Foto</th> <!-- Tambah kolom foto -->
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->gender == "male" ? "Laki-laki" : "Perempuan" }}</td>
                                <td>{{ $employee->employment_period }}</td>
                                <td>{{ $employee->active_status == "1" ? "Aktif" : "Tidak Aktif" }}</td>
                                <td>
                                    @if ($employee->photo)
                                        <img src="{{ asset("storage/photos/" . $employee->photo) }}" alt="Employee Photo"
                                            style="max-width: 100px; max-height: 100px;">
                                    @else
                                        <span class="text-muted">Tidak ada foto</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route("employee.edit", $employee->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route("employee.destroy", $employee) }}" method="POST">
                                        @csrf
                                        @method("delete")
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/datatables-demo.js"></script>
@endpush
