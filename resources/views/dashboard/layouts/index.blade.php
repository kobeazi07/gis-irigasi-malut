<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://js.arcgis.com/4.32/esri/themes/light/main.css">

    <script src="https://js.arcgis.com/4.32/"></script>
    <title>Elegant Dashboard | Dashboard</title>
    <!-- Favicon -->
    @include('dashboard.includes.style')
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
