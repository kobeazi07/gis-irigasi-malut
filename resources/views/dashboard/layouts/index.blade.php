<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Elegant Dashboard | Dashboard</title>
    {{-- <link rel="stylesheet" href="https://js.arcgis.com/4.32/esri/themes/light/main.css"> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <!-- Favicon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- DataTables Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="https://js.arcgis.com/4.32/esri/themes/light/main.css">
    @include('dashboard.includes.style')

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- JavaScript DataTables -->
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.bootstrap5.min.js"></script>


    <script src="https://js.arcgis.com/4.32/"></script>
</head>


<body>
    <div class="layer"></div>
    <!-- ! Body -->
    <a class="skip-link sr-only" href="#skip-target">Skip to content</a>
    <div class="page-flex">
        <!-- ! Sidebar -->
        @include('dashboard.includes.sidebar')
        <div class="main-wrapper">
            <!-- ! Main nav -->
            @include('dashboard.includes.navbar')
            <!-- ! Main -->
            @yield('konten')
            <!-- ! Footer -->
            @include('dashboard.includes.footer')
        </div>
    </div>


    @include('dashboard.includes.script')
</body>

</html>
