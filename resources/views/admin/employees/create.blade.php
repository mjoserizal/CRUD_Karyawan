@extends("layouts.app")

@section("body")
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Karyawan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route("employee.store") }}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="employee_name">Nama karyawan</label>
                                <input type="text" class="form-control @error("employee_name") is-invalid @enderror"
                                    name="employee_name" id="employee_name" placeholder="Nama karyawan...">
                                @error("employee_name")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="position">Jabatan</label>
                                <input type="text" class="form-control @error("position") is-invalid @enderror"
                                    name="position" id="position" placeholder="Jabatan...">
                                @error("position")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control select2-bootstrap4 @error("gender") is-invalid @enderror"
                                    name="gender" id="gender">
                                    <option selected disabled>Pilih</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                                @error("gender")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="employment_period">Masa Kerja</label>
                                <input type="text" class="form-control @error("employment_period") is-invalid @enderror"
                                    name="employment_period" id="employment_period"
                                    placeholder="Pilih rentang masa kerja...">
                                @error("employment_period")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="active">Status</label>
                                <select id="active" class="form-control @error("active") is-invalid @enderror"
                                    name="active">
                                    <option value="1" {{ old("active") == "1" ? "selected" : "" }}>Aktif</option>
                                    <option value="0" {{ old("deactive") == "0" ? "selected" : "" }}>Tidak Aktif
                                    </option>
                                </select>
                                @error("active")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            <!-- File Input untuk Foto Karyawan -->
                            <div class="form-group col-md-6">
                                <label for="photo">Foto Karyawan</label>
                                <input id="photo" name="photo[]" type="file" class="file" data-show-upload="false"
                                    data-show-caption="true">
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
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

            // Select2
            $('#gender').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih',
            });

            // File Input dari Krajee
            $("#photo").fileinput({
                theme: 'fas',
                uploadUrl: "{{ route("employee.upload") }}", // Ganti dengan URL untuk mengunggah file
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
