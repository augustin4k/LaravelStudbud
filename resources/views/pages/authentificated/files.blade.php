@extends('pages.authentificated.profile')

@section('for-profile-content')
    @php
    if (Auth::check() && empty($_GET['id'])) {
        $selected_user_id = auth()->user()->id;
    } elseif (!empty($_GET['id'])) {
        $selected_user_id = $_GET['id'];
    }
    if (Auth::check()) {
        $my_id = auth()->user()->id;
    } else {
        $my_id = 0;
    }
    @endphp

    <v-page-files :my_id="{{ auth()->user()->id }}" :selected_user_id="{{ $selected_user_id }}" type='files'>
    </v-page-files>
@endsection
