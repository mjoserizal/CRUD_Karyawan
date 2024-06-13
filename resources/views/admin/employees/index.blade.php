@extends("layouts.app")

@push("stylesheet")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
@endpush

@section("body")
    <div class="container">
        <a href="{{ route("employee.create") }}" class="btn btn-primary mb-3">Tambah Karyawan</a>

        {{-- DataTables --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Karyawan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Jenis Kelamin</th>
                                <th>Masa Kerja (bulan)</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Placeholder untuk data karyawan --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm">
                        @csrf
                        @method("PUT")
                        <input type="hidden" id="editEmployeeId">
                        <div class="form-group">
                            <label for="editEmployeeName">Nama Karyawan</label>
                            <input type="text" class="form-control" id="editEmployeeName" name="employee_name">
                        </div>
                        <div class="form-group">
                            <label for="editPosition">Jabatan</label>
                            <input type="text" class="form-control" id="editPosition" name="position">
                        </div>
                        <div class="form-group">
                            <label for="editGender">Jenis Kelamin</label>
                            <select class="form-control" id="editGender" name="gender">
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editEmploymentStartDate">Tanggal Mulai Kerja</label>
                            <input type="date" class="form-control" id="editEmploymentStartDate"
                                name="employment_start_date">
                        </div>
                        <div class="form-group">
                            <label for="editEmploymentEndDate">Tanggal Akhir Kerja</label>
                            <input type="date" class="form-control" id="editEmploymentEndDate"
                                name="employment_end_date">
                        </div>
                        <div class="form-group">
                            <label for="editActiveStatus">Status</label>
                            <select class="form-control" id="editActiveStatus" name="active_status">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editPhoto">Foto</label>
                            <input type="file" class="form-control" id="editPhoto" name="photo">
                            <img id="editPhotoPreview" class="img-thumbnail mt-2"
                                style="max-width: 100px; max-height: 100px;">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            var dataTable = $('#dataTable').DataTable({
                searchable: true,
                bPaginate: true,
                ajax: "{{ route("api.employees.index") }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'gender',
                        name: 'gender',
                        render: function(data) {
                            return data === 'male' ? 'Laki-laki' : 'Perempuan';
                        }
                    },
                    {
                        data: 'employment_start_date',
                        name: 'employment_start_date',
                        render: function(data, type, full, meta) {
                            var startDate = moment(data);
                            var endDate = moment(full.employment_end_date);
                            var diffInMonths = endDate.diff(startDate, 'months');
                            return diffInMonths;
                        }
                    },
                    {
                        data: 'active_status',
                        name: 'active_status',
                        render: function(data) {
                            return data === '1' ? 'Aktif' : 'Tidak Aktif';
                        }
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return '<img src="' + data +
                                    '" alt="Employee Photo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">';
                            } else {
                                return '<span class="text-muted">Tidak ada foto</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            var editUrl = "{{ route("employee.edit", ":id") }}".replace(':id',
                                full.id);
                            var deleteUrl = "{{ route("api.employees.destroy", ":id") }}".replace(
                                ':id', full.id);
                            var actions = '<a href="' + editUrl +
                                '" class="btn btn-warning btn-sm">Edit</a> ';
                            actions += '<form action="' + deleteUrl +
                                '" method="POST" class="d-inline">@csrf @method("delete")' +
                                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus karyawan ini?\')">Hapus</button></form>';
                            return actions;
                        }
                    }
                ]
            });

            // Handle delete action
            $('#dataTable tbody').on('click', 'button.btnDelete', function() {
                var row = $(this).closest('tr');
                var employeeId = dataTable.row(row).data().id;

                if (confirm('Apakah Anda yakin ingin menghapus karyawan ini?')) {
                    $.ajax({
                        url: "{{ route("api.employees.destroy", ":id") }}".replace(':id',
                            employeeId),
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            dataTable.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
