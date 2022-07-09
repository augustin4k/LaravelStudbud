@extends('layouts.app')

@section('content-in-body')
    <div id="app" class="bg-light d-flex flex-column gap-5 justify-content-between">
        @if (Auth::check())
            <v-header-auth :user="{{ json_encode(auth()->user()) }}"></v-header-auth>
        @else
            <v-header></v-header>
        @endif
        @yield('content-in-app')
        <v-footer></v-footer>
    </div>
@endsection
