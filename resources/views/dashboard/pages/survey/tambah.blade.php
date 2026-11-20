@extends('dashboard.layouts.index')

@section('konten')
    {{-- ArcGIS SDK --}}


    <style>
        .survey-wrapper {
            width: 100%;
            min-height: auto;
            padding: 20px 12px 50px;
            margin-bottom: 30px;
            background: #f3f7fc;
        }

        .survey-card {
            width: 100%;
            background: #fff;
            border: 1px solid #d6e2f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .04);
        }

        .survey-header {
            padding: 18px 22px;
            color: #fff;
            background-color: rgb(0, 97, 247) !important;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .survey-description {
            padding: 15px 22px;
            color: #64748b;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .survey-content {
            padding: 20px;
        }

        .survey-section {
            height: 100%;
            padding: 18px;
            margin-bottom: 12px;
            background: #fff;
            border: 1px solid #cbdcf2;
            border-radius: 8px;
        }

        .field-label {
            display: block;
            margin-bottom: 12px;
            color: #26364a;
            font-size: 14px;
            font-weight: 600;
        }

        .required {
            color: #dc3545;
        }

        /* Border seluruh input */
        #surveyForm input[type="text"],
        #surveyForm input[type="date"],
        #surveyForm input[type="number"],
        #surveyForm input[type="email"],
        #surveyForm select,
        #surveyForm textarea,
        #surveyForm .survey-control,
        .coordinate-group input {
            display: block;
            width: 100%;
            min-height: 40px;
            padding: 8px 11px;
            color: #334155;
            background-color: #fff !important;
            border: 1px solid #b9cce3 !important;
            border-radius: 5px !important;
            outline: none;
            box-shadow: none !important;
            font-size: 14px;
        }

        #surveyForm input:focus,
        #surveyForm select:focus,
        #surveyForm textarea:focus {
            border-color: rgb(0, 97, 247) !important;
            box-shadow: 0 0 0 2px rgba(0, 97, 247, .10) !important;
        }

        /* Radio */
        .radio-option {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            min-height: 43px;
            padding: 10px 12px;
            margin-bottom: 5px;
            background: #f8fafc;
            border: 1px solid #d4dfec;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .radio-option input {
            width: 16px;
            height: 16px;
            accent-color: rgb(0, 97, 247);
        }

        /* Peta */
        .map-container {
            width: 100%;
        }

        .arcgis-map {
            width: 100%;
            height: 300px;
            border: 1px solid #b9cce3;
            border-radius: 6px;
            overflow: hidden;
        }

        .coordinate-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 12px;
        }

        .coordinate-group span {
            display: block;
            margin-bottom: 5px;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
        }

        .map-note {
            margin-top: 10px;
            color: #64748b;
            font-size: 12px;
        }

        /* Dokumentasi */
        .upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 130px;
            padding: 20px;
            background: #f8fbff;
            border: 2px dashed #b9cce3;
            border-radius: 8px;
            text-align: center;
        }

        .upload-label {
            margin-bottom: 12px;
            color: #475569;
            font-size: 14px;
            cursor: pointer;
        }

        .camera-button {
            width: 43px;
            height: 43px;
            color: #fff;
            background: rgb(0, 97, 247);
            border: none;
            border-radius: 50%;
            font-size: 19px;
            cursor: pointer;
        }

        .photo-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }

        .photo-preview img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        /* Tanda tangan */
        .signature-wrapper {
            position: relative;
            width: 100%;
            height: 180px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
        }

        #signatureCanvas {
            display: block;
            width: 100%;
            height: 180px;
            cursor: crosshair;
            touch-action: none;
        }

        .signature-clear {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            border: none;
            background: #dc3545;
            color: white;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
        }

        .signature-guide {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            color: #adb5bd;
            font-size: 12px;
            pointer-events: none;
        }

        .signature-clear {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            width: 32px;
            height: 32px;
            color: #dc3545;
            background: #fff;
            border: 1px solid #fecaca;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Tombol submit */
        .submit-wrapper {
            display: flex;
            justify-content: flex-end;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .btn-survey-submit {
            min-width: 150px;
            padding: 11px 25px;
            color: #fff;
            background: rgb(0, 97, 247);
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-survey-submit:hover {
            background: #0052d6;
        }

        @media (max-width: 767px) {
            .survey-wrapper {
                padding: 8px 4px 35px;
                margin-bottom: 20px;
            }

            .survey-card {
                margin-bottom: 20px;
            }

            .survey-header {
                padding: 15px;
                font-size: 18px;
            }

            .survey-description {
                padding: 12px 15px;
            }

            .survey-content {
                padding: 10px 8px;
            }

            .survey-section {
                padding: 14px 11px;
                margin-bottom: 10px;
            }

            .arcgis-map {
                height: 260px;
            }

            .coordinate-inputs {
                grid-template-columns: 1fr;
            }

            .submit-wrapper {
                justify-content: stretch;
            }

            .btn-survey-submit {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid survey-wrapper">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 col-xxl-8">

                <div class="survey-card">

                    {{-- HEADER --}}
                    <div class="survey-header">
                        SALURAN
                    </div>

                    <div class="survey-description">
                        Formulir survei kondisi dan informasi saluran.
                    </div>

                    <form id="surveyForm" enctype="multipart/form-data">

                        @csrf

                        <div class="survey-content">
                            <div class="row g-2">

                                {{-- JENIS BANGUNAN --}}
                                <div class="col-12">
                                    <div class="survey-section">

                                        <label class="field-label">
                                            Jenis Bangunan
                                            <span class="required">*</span>
                                        </label>

                                        <div class="row g-2">

                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="jenis_bangunan" value="Bangunan Utama"
                                                        required>
                                                    Bangunan Utama
                                                </label>
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="jenis_bangunan" value="Bangunan Irigasi">
                                                    Bangunan Irigasi
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- NAMA SALURAN --}}
                                <div class="col-12">
                                    <div class="survey-section">

                                        <label class="field-label" for="nama_saluran">
                                            Tipe Bangunan
                                            <span class="required">*</span>
                                        </label>

                                        <input type="text" name="tipe_bangunan" id="tipe_bangunan" class="survey-control"
                                            placeholder="Masukkan tipe bangunan" required>

                                    </div>
                                </div>

                                {{-- FUNGSI BANGUNAN --}}
                                <div class="col-12">
                                    <div class="survey-section">

                                        <label class="field-label">
                                            Fungsi Bangunan
                                            <span class="required">*</span>
                                        </label>

                                        <div class="row g-2">

                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="fungsi_bangunan" value="Berfungsi" required>
                                                    Berfungsi
                                                </label>
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="fungsi_bangunan" value="Tidak Berfungsi">
                                                    Tidak Berfungsi
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- KONDISI BANGUNAN --}}
                                <div class="col-12">
                                    <div class="survey-section">

                                        <label class="field-label">
                                            Kondisi Bangunan
                                            <span class="required">*</span>
                                        </label>

                                        <div class="row g-2">

                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="kondisi_bangunan" value="Baik" required>
                                                    Baik
                                                </label>
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="kondisi_bangunan" value="Rusak">
                                                    Rusak
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="kondisi_bangunan" value="Rusak">
                                                    Rusak Ringan
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="kondisi_bangunan" value="Rusak">
                                                    Rusak Sedang
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="kondisi_bangunan" value="Rusak">
                                                    Rusak Berat
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="radio-option">
                                                    <input type="radio" name="kondisi_bangunan" value="Rusak">
                                                    Tidak Dinilai
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- DOKUMENTASI --}}
                                {{-- <div class="col-12">
                                    <div class="survey-section">

                                        <label class="field-label">
                                            Dokumentasi
                                            <span class="required">*</span>
                                        </label>

                                        <div class="upload-area">

                                            <label class="upload-label" for="dokumentasi">
                                                Letakkan gambar di sini atau pilih gambar
                                            </label>

                                            <button type="button" class="camera-button"
                                                onclick="document.getElementById('dokumentasi').click()">
                                                📷
                                            </button>

                                        </div>

                                        <input type="file" name="dokumentasi[]" id="dokumentasi" accept="image/*"
                                            multiple required hidden>

                                        <div id="photoPreview" class="photo-preview"></div>

                                    </div>
                                </div> --}}

                                {{-- TANGGAL SURVEI --}}
                                <div class="col-12 col-md-6">
                                    <div class="survey-section">
                                        <label class="field-label" for="tanggal_survei">
                                            Tanggal Survei
                                            <span class="required">*</span>
                                        </label>

                                        <input type="date" name="tanggal_survei" id="tanggal_survei" class="form-control"
                                            required>
                                    </div>
                                </div>

                                {{-- SURVEYOR --}}
                                <div class="col-12">
                                    <div class="survey-section">

                                        <label class="field-label">
                                            Petugas Survei
                                            <span class="required">*</span>
                                        </label>

                                        <div class="signature-wrapper">

                                            <canvas id="signatureCanvas"></canvas>

                                            <button type="button" class="signature-clear" id="clearSignature"
                                                title="Hapus tanda tangan">
                                                🗑
                                            </button>

                                            <div class="signature-guide">
                                                Harap tanda tangan di atas garis
                                            </div>

                                        </div>

                                        <input type="hidden" name="ttd_surveyor" id="ttd_surveyor">

                                        <small class="text-muted">
                                            Tanda tangan wajib diisi sebelum mengirim survei.
                                        </small>

                                    </div>
                                </div>
                                {{-- KOORDINAT --}}
                                {{-- <div class="col-12">
                                    <hr>
                                    <h6>Lokasi Bangunan</h6>
                                    <p class="text-muted">
                                        Klik lokasi bangunan pada peta untuk menentukan koordinat.
                                    </p>
                                </div> --}}
                                {{-- KOORDINAT --}}
                                {{-- <div class="col-12">
                                    <hr>
                                    <h6>Lokasi Bangunan</h6>
                                    <p class="text-muted">
                                        Klik lokasi bangunan pada peta untuk menentukan koordinat.
                                    </p>
                                </div> --}}
                                <div class="col-12 ">
                                    <button type="button " id="btnAmbilGPS" class="btn btn-survey-submit mb-5 mt-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                                        </svg>
                                        Ambil Lokasi GPS
                                    </button>

                                    <span id="gpsStatus" class="text-muted ms-2"></span>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input type="number" step="any" name="latitude" id="latitude"
                                        class="form-control" placeholder="Latitude" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input type="number" step="any" name="longitude" id="longitude"
                                        class="form-control" placeholder="Longitude" required>
                                </div>
                                {{-- SUBMIT --}}
                                <div class="col-12">
                                    <div class="submit-wrapper">

                                        <button type="submit" class="btn-survey-submit" id="btnSaveSurveiBangunan">
                                            Kirim
                                        </button>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ArcGIS SDK --}}
    <script src="https://js.arcgis.com/4.32/"></script>
    <script></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | 1. PETA ARCGIS
            |--------------------------------------------------------------------------
            */

            require([
                "esri/Map",
                "esri/views/MapView",
                "esri/Graphic"
            ], function(Map, MapView, Graphic) {

                const map = new Map({
                    basemap: "streets-navigation-vector"
                });

                const view = new MapView({
                    container: "mapLokasi",
                    map: map,
                    center: [127.3782, 0.7823],
                    zoom: 13
                });

                let marker = null;

                // Fungsi menampilkan lokasi
                function tampilkanLokasi(latitude, longitude) {

                    document.getElementById('latitude').value =
                        latitude.toFixed(7);

                    document.getElementById('longitude').value =
                        longitude.toFixed(7);

                    const point = {
                        type: "point",
                        latitude: latitude,
                        longitude: longitude
                    };

                    if (marker) {
                        view.graphics.remove(marker);
                    }

                    marker = new Graphic({
                        geometry: point,
                        symbol: {
                            type: "simple-marker",
                            color: [0, 97, 247],
                            size: 13,
                            outline: {
                                color: "#ffffff",
                                width: 2
                            }
                        }
                    });

                    view.graphics.add(marker);

                    view.goTo({
                        center: [longitude, latitude],
                        zoom: 17
                    });
                }

                // Klik peta untuk mengambil koordinat
                view.when(function() {

                    view.on("click", function(event) {

                        tampilkanLokasi(
                            event.mapPoint.latitude,
                            event.mapPoint.longitude
                        );

                    });

                });

                // Ambil koordinat GPS
                document.getElementById('btnAmbilGPS')
                    .addEventListener('click', function() {

                        const status = document.getElementById('gpsStatus');

                        if (!navigator.geolocation) {
                            status.textContent = 'Browser tidak mendukung GPS.';
                            return;
                        }

                        status.textContent = 'Sedang mengambil lokasi GPS...';

                        navigator.geolocation.getCurrentPosition(

                            function(position) {

                                const latitude = position.coords.latitude;
                                const longitude = position.coords.longitude;

                                tampilkanLokasi(latitude, longitude);

                                status.textContent =
                                    'Lokasi berhasil ditemukan. Akurasi ±' +
                                    Math.round(position.coords.accuracy) + ' meter';

                            },

                            function(error) {

                                let message = '';

                                switch (error.code) {
                                    case error.PERMISSION_DENIED:
                                        message =
                                            'Izin lokasi ditolak. Aktifkan izin lokasi browser.';
                                        break;

                                    case error.POSITION_UNAVAILABLE:
                                        message = 'Lokasi GPS tidak tersedia.';
                                        break;

                                    case error.TIMEOUT:
                                        message = 'Waktu pengambilan GPS habis. Coba kembali.';
                                        break;

                                    default:
                                        message = 'Terjadi kesalahan saat mengambil GPS.';
                                }

                                status.textContent = message;

                            },

                            {
                                enableHighAccuracy: true,
                                timeout: 15000,
                                maximumAge: 0
                            }

                        );

                    });

            });
            /*
            |--------------------------------------------------------------------------
            | 2. PREVIEW FOTO DOKUMENTASI
            |--------------------------------------------------------------------------
            */

            const dokumentasi = document.getElementById('dokumentasi');
            const photoPreview = document.getElementById('photoPreview');

            if (dokumentasi && photoPreview) {

                dokumentasi.addEventListener('change', function() {

                    photoPreview.innerHTML = '';

                    const files = Array.from(this.files);

                    files.forEach(function(file) {

                        if (!file.type.startsWith('image/')) {
                            return;
                        }

                        const imageUrl = URL.createObjectURL(file);

                        const img = document.createElement('img');

                        img.src = imageUrl;
                        img.alt = 'Preview dokumentasi';

                        img.onload = function() {
                            URL.revokeObjectURL(imageUrl);
                        };

                        photoPreview.appendChild(img);

                    });

                });

            }


            /*
            |--------------------------------------------------------------------------
            | 3. SIGNATURE PAD
            |--------------------------------------------------------------------------
            */

            const canvas = document.getElementById('signatureCanvas');

            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            const clearButton = document.getElementById('clearSignature');
            const signatureInput = document.getElementById('ttd_surveyor');
            const form = document.getElementById('surveyForm');

            let drawing = false;
            let hasSignature = false;

            function configureCanvas() {

                const rect = canvas.getBoundingClientRect();

                if (!rect.width) return;

                const ratio = Math.max(window.devicePixelRatio || 1, 1);

                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;

                ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.strokeStyle = '#172b4d';
            }

            configureCanvas();

            function getPosition(event) {

                const rect = canvas.getBoundingClientRect();

                return {
                    x: event.clientX - rect.left,
                    y: event.clientY - rect.top
                };
            }

            // Mulai menggambar
            canvas.addEventListener('pointerdown', function(event) {

                event.preventDefault();

                drawing = true;
                hasSignature = true;

                canvas.setPointerCapture(event.pointerId);

                const pos = getPosition(event);

                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                ctx.lineTo(pos.x + 0.1, pos.y + 0.1);
                ctx.stroke();

            });

            // Proses menggambar
            canvas.addEventListener('pointermove', function(event) {

                if (!drawing) return;

                event.preventDefault();

                const pos = getPosition(event);

                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();

            });

            // Selesai menggambar
            function stopDrawing() {

                drawing = false;
                ctx.closePath();

            }

            canvas.addEventListener('pointerup', stopDrawing);
            canvas.addEventListener('pointercancel', stopDrawing);
            canvas.addEventListener('lostpointercapture', stopDrawing);


            /*
            |--------------------------------------------------------------------------
            | 4. HAPUS TANDA TANGAN
            |--------------------------------------------------------------------------
            */

            if (clearButton) {

                clearButton.addEventListener('click', function() {

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    hasSignature = false;
                    signatureInput.value = '';

                });

            }


            /*
            |--------------------------------------------------------------------------
            | 5. RESIZE CANVAS
            |--------------------------------------------------------------------------
            */

            window.addEventListener('resize', function() {

                // Jangan menghapus tanda tangan yang sudah dibuat.
                if (hasSignature) return;

                configureCanvas();

            });


            /*
            |--------------------------------------------------------------------------
            | 6. VALIDASI FORM DAN SIMPAN TANDA TANGAN
            |--------------------------------------------------------------------------
            */


        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('tanggal_survei');

            dateInput.addEventListener('click', function() {
                if (this.showPicker) {
                    this.showPicker();
                }
            });
        });



        $('#btnSaveSurveiBangunan').on('click', function() {
            let form = document.getElementById('surveyForm');
            let formData = new FormData(form);


            $.ajax({
                url: "{{ route('SimpanSurvey') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {
                    $('#btnSaveSurveiBangunan')
                        .prop('disabled', true)
                        .text('Menyimpan...');
                },

                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'maps berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },

                error: function(xhr) {
                    let pesan = 'Terjadi kesalahan';

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        pesan = '';
                        for (let key in errors) {
                            pesan += `• ${errors[key][0]}<br>`;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: pesan
                    });
                },

                complete: function() {
                    $('#btnSaveSurveiBangunan')
                        .prop('disabled', false)
                        .text('Simpan Survey');
                }
            });
        });
    </script>
@endsection
