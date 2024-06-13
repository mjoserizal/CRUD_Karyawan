@extends("layouts.app")

@section("body")
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Karyawan</h6>
                </div>
                <div class="card-body">
                    <form id="employeeForm" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="employee_name">Nama karyawan</label>
                                <input type="text" class="form-control" name="employee_name" id="employee_name"
                                    placeholder="Nama karyawan...">
                                <div class="invalid-feedback" id="employee_name_error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="position">Jabatan</label>
                                <input type="text" class="form-control" name="position" id="position"
                                    placeholder="Jabatan...">
                                <div class="invalid-feedback" id="position_error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option selected disabled>Pilih</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                                <div class="invalid-feedback" id="gender_error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="employment_period">Masa Kerja</label>
                                <input type="text" class="form-control" name="employment_period" id="employment_period"
                                    placeholder="Pilih rentang masa kerja...">
                                <div class="invalid-feedback" id="employment_period_error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="active">Status</label>
                                <select id="active" class="form-control" name="active">
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                                <div class="invalid-feedback" id="active_error"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="photo">Foto Karyawan</label>
                                <input id="photo" name="photo[]" type="file" class="file" data-show-upload="false"
                                    data-show-caption="true">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" id="submitForm" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        $(document).ready(function() {
            $('#submitForm').click(function() {
                var formData = new FormData($('#employeeForm')[0]);

                $.ajax({
                    url: "{{ route("employee.store") }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success response
                        console.log(response.message); // Pesan sukses dari server
                        // Redirect to dashboard
                        window.location.href =
                            '/employee'; // Mengarahkan langsung ke /dashboard
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseJSON.message);
                    }
                });
            });


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

            // Select2
            $('#gender').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih',
            });

            // File Input dari Krajee
            $("#photo").fileinput({
                theme: 'fas',
                uploadUrl: "{{ route("api.employees.upload") }}", // Ganti dengan URL untuk mengunggah file
                showUpload: false,
                showCaption: true,
                browseClass: "btn btn-primary",
                fileType: "any",
                previewFileIcon: '<i class="fas fa-file"></i>',
                overwriteInitial: false,
                initialPreviewAsData: true,
                initialPreviewFileType: 'image', // Jenis file yang ditampilkan di awal
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                preferIconicPreview: true,
                previewFileIconSettings: {
                    'jpg': '<i class="fas fa-file-image"></i>',
                    'png': '<i class="fas fa-file-image"></i>',
                    'gif': '<i class="fas fa-file-image"></i>',
                    'jpeg': '<i class="fas fa-file-image"></i>'
                },
                previewSettings: {
                    image: {
                        width: "auto",
                        height: "160px"
                    }
                }
            });

            // Dropzone.js untuk file PDF
        });
    </script>
@endpush
