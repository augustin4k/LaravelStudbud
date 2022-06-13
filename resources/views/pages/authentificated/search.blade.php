@extends('layouts.auth')

@section('content-in-app-auth')
    <v-search :get_users='{{ json_encode($users) }}' :name_search='{{ json_encode($name_search) }}'></v-search>
@endsection
