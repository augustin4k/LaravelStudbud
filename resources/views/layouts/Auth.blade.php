@extends('layouts.app')

@section('content-in-body')
    <div id="app" class="bg-light">
        @if (session()->get('token') !== null)
            <input class="d-none" type="hidden" id='token_access' value="{{ session()->get('token') }}">
        @endif
        <div class="w-100" id="navbar-authentificated-mood">
            @if (Auth::check())
                <v-header-auth :user="{{ json_encode(auth()->user()) }}"></v-header-auth>
            @else
                <v-header></v-header>
            @endif
        </div>
        <div class="content-under-header container mt-3">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    @if (Auth::check())
                        <v-left-bar :my_id='{{ auth()->user()->id }}'></v-left-bar>
                    @endif
                </div>
                <div class="col-12 col-lg-9 main"> @yield('content-in-app-auth') </div>
            </div>
        </div>
    </div>
@endsection
