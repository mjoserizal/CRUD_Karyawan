@extends("layouts.app")

@section("body")
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Data Karyawan</h6>
                </div>
                <div class="card-body">
                    <form id="employeeForm" enctype="multipart/form-data">
                        @method("PUT")
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="employee_name">Nama karyawan</label>
                                <input type="text" class="form-control @error("employee_name") is-invalid @enderror"
                                    name="employee_name" value="{{ old("employee_name", $employee->employee_name) }}"
                                    id="employee_name" placeholder="Nama karyawan...">

                                @error("employee_name")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="position">Jabatan</label>
                                <input type="text" class="form-control @error("position") is-invalid @enderror"
                                    name="position" value="{{ old("position", $employee->position) }}" id="position"
                                    placeholder="Jabatan...">

                                @error("position")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control select2-bootstrap4 @error("gender") is-invalid @enderror"
                                    name="gender" id="gender">
                                    <option selected disabled>Pilih</option>
                                    <option value="male" {{ $employee->gender == "male" ? "selected" : "" }}>Laki-laki
                                    </option>
                                    <option value="female" {{ $employee->gender == "female" ? "selected" : "" }}>Perempuan
                                    </option>
                                </select>
                                @error("gender")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="employment_period">Masa Kerja</label>
                                <input type="text" class="form-control @error("employment_period") is-invalid @enderror"
                                    name="employment_period" id="employment_period"
                                    value="{{ old("employment_period", $employee->employment_start_date . " - " . $employee->employment_end_date) }}"
                                    placeholder="Pilih rentang masa kerja...">

                                @error("employment_period")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="active_status">Status</label>
                                <select id="active_status"
                                    class="custom-select @error("active_status") is-invalid @enderror" name="active_status">
                                    <option selected disabled>Pilih</option>
                                    <option value="1" {{ $employee->active_status == "1" ? "selected" : "" }}>Aktif
                                    </option>
                                    <option value="0" {{ $employee->active_status == "0" ? "selected" : "" }}>Tidak
                                        aktif</option>
                                </select>

                                @error("active_status")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" id="submitForm" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
@endsection

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            // Date Range Picker
            $('#employment_period').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('#employment_period').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#employment_period').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('#gender').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih',
            });
            $('#submitForm').click(function(e) {
                e.preventDefault();

                var formData = new FormData($('#employeeForm')[0]);

                $.ajax({
                    url: "{{ route("api.employees.update", $employee->id) }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response.message);
                        window.location.href = response.redirect;
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush
