@extends('layouts.app')
@push('customCss')
    <link rel="stylesheet" href="/css/sidebars.css">
@endpush
@push('scripts')
    <script src="/js/sidebars.js"></script>
@endpush

@section('content-in-body')
    <div id="app">
        <div class="container-fluid">
            <div class="row admin-panel">
                <div class="bg-dark text-dark col-12 col-lg-3 col-xl-2">
                    <v-sidebar :user_info='{{ json_encode(auth()->user()) }}'></v-sidebar>
                </div>
                <div class="col-12 col-lg-9 col-xl-10 h-100 overflow-auto">
                    @yield('right-wrapper-admin-panel')
                </div>
            </div>
        </div>
    </div>
@endsection
