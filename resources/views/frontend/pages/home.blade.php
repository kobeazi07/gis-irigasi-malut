@extends('frontend.layouts.index')

@section('konten')
    <div class="hero-2 overlay" style="background-image: url('{{ asset('assets/frontend/images/hero.jpg') }}');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mx-auto">
                    <h1 class="mb-5"><span>GIS</span> <span class="d-block">Irigasi</span> <span class="d-block">Provinsi
                            Maluku Utara</span></h1>
                    <div class="col-md-12 text-center mt-5">
                        <p><a href="{{ route('HalamanMap') }}" class="btn btn-primary me-4">Dashboard GIS</a></p>
                    </div>

                    <div class="intro-desc">
                        <div class="line"></div>
                        <p>Delectus voluptatum distinctio quos eius excepturi sunt pariatur, aut, doloribus officia ea
                            molestias beatae laudantium, quam odio ipsum veritatis est maiores velit quasi blanditiis et
                            natus accusamus itaque.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
