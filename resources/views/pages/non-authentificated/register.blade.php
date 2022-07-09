@extends('layouts.NonAuth')

@section('content-in-app')
    <div class="container">
        <div class="d-flex flex-column gap-4 col-12 offset-0
            col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="text-center d-flex gap-2 align-items-center justify-content-center">
                <i class="fa fa-graduation-cap fa-3x" aria-hidden="true"></i>
                <h1 class="h3 font-weight-normal">Sign-up</h1>
            </div>
            <!-- pentru lista erorilor dupa validarea php -->
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <v-register type='register' name-route="{{ route('user-register') }}" csrf-token="{{ csrf_token() }}">
            </v-register>
        </div>
    </div>
@endsection
