@extends('layouts.auth')

@push('customCss')
    <link rel="stylesheet" href="/css/chat.css">
    <style>
        #app {
            height: 100vh !important;
        }

    </style>
@endpush
@push('scripts')
    <script src="/js/chat.js"></script>
@endpush

{{-- cod php --}}
@php
if (Auth::check() && empty($_GET['id'])) {
    $selected_user = 0;
} else {
    $selected_user = $_GET['id'];
}

@endphp
@section('content-in-app-auth')
    <v-chat :selected_user_id='{{ json_encode($selected_user) }}'
        :my_avatar_path='{{ json_encode(auth()->user()->avatar_path) }}'>
    </v-chat>
@endsection
