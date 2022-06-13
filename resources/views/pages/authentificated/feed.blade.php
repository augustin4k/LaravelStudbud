@extends('layouts.auth')

@push('customCss')
    <link rel="stylesheet" href="/css/header-profile.css">
@endpush

@section('content-in-app-auth')
    <v-page-feed :my_id="{{ auth()->user()->id }}" :selected_user_id={{ 0 }}></v-page-feed>
@endsection
