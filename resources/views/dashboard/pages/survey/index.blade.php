@extends('dashboard.layouts.index')

@section('konten')
    <style>
        .survey-page {
            padding: 20px;
        }

        .survey-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .survey-page-title {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        .survey-page-subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #888;
        }

        .btn-add-survey {
            display: inline-block;
            padding: 10px 16px;
            background: #2e8730;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-add-survey:hover {
            background: #246e26;
            color: #fff;
        }

        .survey-table-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .survey-table {
            width: 100%;
            border-collapse: collapse;
        }

        .survey-table thead {
            background: #f5f7f5;
        }

        .survey-table th {
            padding: 14px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            border-bottom: 1px solid #ddd;
        }

        .survey-table td {
            padding: 14px 12px;
            font-size: 13px;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .survey-table tbody tr:hover {
            background: #f9fcf9;
        }

        .survey-table .text-center {
            text-align: center;
        }

        .survey-type {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        .type-primer {
            background: #e4f3e5;
            color: #2e8730;
        }

        .type-sekunder {
            background: #e5efff;
            color: #2863a8;
        }

        .type-lainnya {
            background: #fff1dc;
            color: #b7791f;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .survey-page {
                padding: 12px;
            }

            .survey-table-card {
                padding: 12px;
            }

            .survey-page-title {
                font-size: 19px;
            }
        }
    </style>

    <div class="survey-page">

        {{-- HEADER --}}
        <div class="survey-page-header">

            <div>
                <h4 class="survey-page-title">
                    Data Survey Saluran
                </h4>

                <div class="survey-page-subtitle">
                    Daftar hasil pendataan dan survei kondisi saluran.
                </div>
            </div>

            <a href="{{ route('HalamanTambahSurvey') }}" class="btn-add-survey">
                + Tambah Survey
            </a>

        </div>


        {{-- TABLE --}}
        <div class="container-fluid">

            <div class="card">

                <div class="card-header">
                    <h5>Data Survei Bangunan</h5>
                </div>

                <div class="card-body">

                    @if (isset($error))
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endif

                    <div class="table-responsive">

                        <table id="surveyTable" class="table table-bordered table-striped table-hover align-middle w-100">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Object ID</th>
                                    <th>Jenis Bangunan</th>
                                    <th>Tipe Bangunan</th>
                                    <th>Fungsi</th>
                                    <th>Kondisi</th>
                                    <th>Tanggal Survei</th>
                                    <th>Koordinat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($surveys as $index => $survey)
                                    @php
                                        $attributes = $survey['attributes'] ?? [];
                                        $geometry = $survey['geometry'] ?? [];
                                    @endphp

                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            {{ $attributes['objectid'] ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $attributes['jenis_bangunan'] ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $attributes['tipe_bangunan'] ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $attributes['fungsi_bangunan'] ?? '-' }}
                                        </td>

                                        <td>
                                            @php
                                                $kondisi = $attributes['kondisi_bangunan'] ?? '-';
                                            @endphp

                                            @if ($kondisi === 'Baik')
                                                <span class="badge bg-success">{{ $kondisi }}</span>
                                            @elseif($kondisi === 'Rusak Ringan')
                                                <span class="badge bg-warning text-dark">{{ $kondisi }}</span>
                                            @elseif($kondisi === 'Rusak Sedang')
                                                <span class="badge bg-warning text-dark">{{ $kondisi }}</span>
                                            @elseif($kondisi === 'Rusak Berat')
                                                <span class="badge bg-danger">{{ $kondisi }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $kondisi }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($attributes['tanggal_survei']))
                                                {{ \Carbon\Carbon::createFromTimestampMs($attributes['tanggal_survei'])->format('d-m-Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if (isset($geometry['x'], $geometry['y']))
                                                {{ $geometry['y'] }},
                                                {{ $geometry['x'] }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16">
                                                    <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                    <path
                                                        d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0" />
                                                </svg>
                                            </button>

                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data survei.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
        <script>
            $(document).ready(function() {

                $('#surveyTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],

                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data tersedia",
                        zeroRecords: "Data tidak ditemukan",
                        emptyTable: "Belum ada data survei",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },

                    columnDefs: [{
                        targets: 0,
                        orderable: false,
                        searchable: false
                    }]
                });

            });
        </script>
    @endsection
