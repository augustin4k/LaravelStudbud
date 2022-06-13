@extends('layouts.AdminPanel')

@section('right-wrapper-admin-panel')
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100">
                <button class="btn btn-dark d-none d-lg-block  disabled" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                    <i class="fa fa-bars fa-1x"></i>
                </button>
                <button class="d-lg-none btn btn-dark" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                    <i class="fa fa-bars fa-1x"></i>
                </button>
            </div>
        </div>
    </nav>
    <div class="py-3 d-flex justify-content-center">
        <div class="alert alert-success w3-animate-opacity" role="alert">
            <strong>Salut, tocmai ai fost logat ca administrator!</strong>
        </div>

    </div>
@endsection
