@extends('layouts.NonAuth')

@section('content-in-app')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white fw-bold">{{ __('Tocmai ai primit un email!') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('Un nou link a fost trimit pe email-ul tau pentru a-ti activa contul.') }}
                            </div>
                        @endif

                        {{ __('Verifica email-ul pentru a-ti activa contul.') }}
                        {{ __('Daca nu ai primit niciun email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit"
                                class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <a href='{{ route('logout') }}' type="button" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
