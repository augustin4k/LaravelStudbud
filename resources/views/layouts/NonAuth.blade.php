@extends('layouts.app')

@section('content-in-body')
    <div id="app" class="bg-light d-flex flex-column gap-5 justify-content-between">
        <v-header></v-header>
        @yield('content-in-app')
        <v-footer></v-footer>
    </div>
@endsection
