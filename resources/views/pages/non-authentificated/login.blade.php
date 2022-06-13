@extends('layouts.NonAuth')

@section('content-in-app')
    <div class="container">
        <div class="form-signin">
            <div
                class="
                text-center
                gap-2
                mb-3
                d-flex
                align-items-center
                justify-content-center
              ">
                <i class="fa fa-graduation-cap fa-3x" aria-hidden="true"></i>
                <h1 class="h3 font-weight-normal">Logare</h1>
            </div>

            @error('user-no-exist')
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">{{ $message }}</p>
                </div>
            @enderror

            @if (session('message-about-authentification'))
                <div class="alert alert-success hstack gap-2" role="alert">
                    <i class="bi bi-envelope-fill"></i>
                    <p class="mb-0">{{ session('message-about-authentification') }}</p>
                </div>
            @endif
            <v-login name-route='{{ route('user-login') }}' csrf-token="{{ csrf_token() }}"></v-login>
        </div>
    </div>
@endsection
