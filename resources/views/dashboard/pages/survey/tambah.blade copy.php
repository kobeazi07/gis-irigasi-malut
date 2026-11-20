@extends('dashboard.layouts.index')

@section('konten')
{{-- ArcGIS SDK --}}
<link rel="stylesheet" href="https://js.arcgis.com/4.32/esri/themes/light/main.css">

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
        background: #fff;
        border: 1px solid #b9cce3;
        border-radius: 6px;
        overflow: hidden;
    }

    #signatureCanvas {
        display: block;
        width: 100%;
        height: 180px;
        cursor: crosshair;
        touch-action: none;
    }

    .signature-guide {
        padding: 8px;
        color: #94a3b8;
        background: #f8fafc;
        border-top: 1px dashed #cbd5e1;
        font-size: 12px;
        text-align: center;
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

                <form id="surveyForm" action="" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="survey-content">
                        <div class="row g-2">

                            {{-- JENIS SALURAN --}}
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
                            {{--
                                {{-- TITIK AWAL --}}
                            <div class="col-12 col-lg-6">
                                <div class="survey-section">

                                    <label class="field-label">
                                        Titik Awal
                                    </label>

                                    <div class="map-container">
                                        <div id="mapAwal" class="arcgis-map"></div>

                                        <div class="coordinate-inputs">

                                            <div class="coordinate-group">
                                                <span>Lintang (Latitude)</span>
                                                <input type="number" step="any" name="titik_awal_lat"
                                                    id="titik_awal_lat" placeholder="Latitude" required>
                                            </div>

                                            <div class="coordinate-group">
                                                <span>Bujur (Longitude)</span>
                                                <input type="number" step="any" name="titik_awal_lng"
                                                    id="titik_awal_lng" placeholder="Longitude" required>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="map-note">
                                        Klik peta untuk menentukan titik awal.
                                    </div>

                                </div>
                            </div>

                            {{-- TITIK AKHIR --}}
                            <div class="col-12 col-lg-6">
                                <div class="survey-section">

                                    <label class="field-label">
                                        Titik Akhir
                                    </label>

                                    <div class="map-container">
                                        <div id="mapAkhir" class="arcgis-map"></div>

                                        <div class="coordinate-inputs">

                                            <div class="coordinate-group">
                                                <span>Lintang (Latitude)</span>
                                                <input type="number" step="any" name="titik_akhir_lat"
                                                    id="titik_akhir_lat" placeholder="Latitude" required>
                                            </div>

                                            <div class="coordinate-group">
                                                <span>Bujur (Longitude)</span>
                                                <input type="number" step="any" name="titik_akhir_lng"
                                                    id="titik_akhir_lng" placeholder="Longitude" required>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="map-note">
                                        Klik peta untuk menentukan titik akhir.
                                    </div>

                                </div>
                            </div> --}}

                            {{-- DOKUMENTASI --}}
                            <div class="col-12">
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
                            </div>

                            {{-- TANGGAL SURVEI --}}
                            <div class="col-12 col-md-6">
                                <div class="survey-section">

                                    <label class="field-label" for="tanggal_survei">
                                        Tanggal Survei
                                        <span class="required">*</span>
                                    </label>

                                    <input type="date" name="tanggal_survei" id="tanggal_survei"
                                        class="survey-control" required>

                                </div>
                            </div>

                            {{-- SURVEYOR --}}
                            <div class="col-12">
                                <div class="survey-section">

                                    <label class="field-label">
                                        Surveyor
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

                                </div>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="col-12">
                                <div class="submit-wrapper">

                                    <button type="submit" class="btn-survey-submit">
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

            const defaultCenter = [127.3782, 0.7823];

            function createSurveyMap(elementId, latId, lngId) {

                const map = new Map({
                    basemap: "streets-navigation-vector"
                });

                const view = new MapView({
                    container: elementId,
                    map: map,
                    center: defaultCenter,
                    zoom: 13
                });

                let marker = null;

                view.when(function() {

                    view.on("click", function(event) {

                        const latitude = event.mapPoint.latitude;
                        const longitude = event.mapPoint.longitude;

                        document.getElementById(latId).value =
                            latitude.toFixed(7);

                        document.getElementById(lngId).value =
                            longitude.toFixed(7);

                        if (marker) {
                            view.graphics.remove(marker);
                        }

                        marker = new Graphic({
                            geometry: event.mapPoint,
                            symbol: {
                                type: "simple-marker",
                                color: [0, 97, 247],
                                size: 12,
                                outline: {
                                    color: "#ffffff",
                                    width: 2
                                }
                            }
                        });

                        view.graphics.add(marker);
                    });

                });

                return view;
            }

            const mapAwal = createSurveyMap(
                "mapAwal",
                "titik_awal_lat",
                "titik_awal_lng"
            );

            const mapAkhir = createSurveyMap(
                "mapAkhir",
                "titik_akhir_lat",
                "titik_akhir_lng"
            );

        });


        /*
        |--------------------------------------------------------------------------
        | 2. PREVIEW FOTO DOKUMENTASI
        |--------------------------------------------------------------------------
        */

        const dokumentasi = document.getElementById('dokumentasi');
        const photoPreview = document.getElementById('photoPreview');

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

                photoPreview.appendChild(img);

            });

        });


        /*
        |--------------------------------------------------------------------------
        | 3. SIGNATURE PAD
        |--------------------------------------------------------------------------
        */

        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');

        const clearButton = document.getElementById('clearSignature');
        const signatureInput = document.getElementById('ttd_surveyor');

        let drawing = false;
        let hasSignature = false;

        function resizeCanvas() {

            const rect = canvas.getBoundingClientRect();
            const oldCanvas = document.createElement('canvas');

            oldCanvas.width = canvas.width;
            oldCanvas.height = canvas.height;

            oldCanvas.getContext('2d').drawImage(canvas, 0, 0);

            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            canvas.width = rect.width * ratio;
            canvas.height = 180 * ratio;

            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#172b4d';

            if (oldCanvas.width && oldCanvas.height && hasSignature) {
                ctx.drawImage(
                    oldCanvas,
                    0,
                    0,
                    oldCanvas.width,
                    oldCanvas.height,
                    0,
                    0,
                    rect.width,
                    180
                );
            }
        }

        function getPosition(event) {

            const rect = canvas.getBoundingClientRect();

            return {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top
            };
        }

        canvas.addEventListener('pointerdown', function(event) {

            drawing = true;
            hasSignature = true;

            canvas.setPointerCapture(event.pointerId);

            const position = getPosition(event);

            ctx.beginPath();
            ctx.moveTo(position.x, position.y);

        });

        canvas.addEventListener('pointermove', function(event) {

            if (!drawing) return;

            const position = getPosition(event);

            ctx.lineTo(position.x, position.y);
            ctx.stroke();

        });

        function stopDrawing() {
            drawing = false;
            ctx.closePath();
        }

        canvas.addEventListener('pointerup', stopDrawing);
        canvas.addEventListener('pointercancel', stopDrawing);
        canvas.addEventListener('pointerleave', stopDrawing);

        clearButton.addEventListener('click', function() {

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            hasSignature = false;
            signatureInput.value = '';

        });

        resizeCanvas();

        window.addEventListener('resize', resizeCanvas);


        /*
        |--------------------------------------------------------------------------
        | 4. VALIDASI FORM DAN SIMPAN TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        const form = document.getElementById('surveyForm');

        form.addEventListener('submit', function(event) {

            if (!hasSignature) {

                event.preventDefault();

                alert('Silakan isi tanda tangan surveyor terlebih dahulu.');

                return;
            }

            signatureInput.value = canvas.toDataURL('image/png');

        });

    });
</script>

{{-- SWEETALERT: hanya tampil jika ada flash session --}}
@if (session()->has('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#0061F7'
            });
        }
    });
</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Validasi gagal',
                text: @json($errors - > first()),
                confirmButtonColor: '#0061F7'
            });
        }
    });
</script>
@endif
@endsection