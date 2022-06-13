@extends('layouts.NonAuth')

@section('content-in-app')
    <div class="container">
        <div class="form-signin vstack gap-4">
            <div class="text-center d-flex gap-2 align-items-center justify-content-center">
                <i class="fa fa-graduation-cap fa-3x" aria-hidden="true"></i>
                <h1 class="h3 font-weight-normal">Reset password</h1>
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
            @if (!empty($token))
                <form action="{{ route('password.update') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name='token' value={{ $token }}>
                    <div class="form-floating">
                        <input type="email" class="form-control" name='email' id="email" placeholder="name@example.com"
                            required />
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name='password' id="password"
                            placeholder="name@example.com" required />
                        <label for="password">Parola noua</label>
                    </div>
                    <small class="text-muted">* Intre 4 si 12 caractere</small>
                    <div class="form-floating">
                        <input type="password" class="form-control" name='password_confirmation' id="password_confirm"
                            placeholder="name@example.com" required />
                        <label for="password_confirmation">Confirma parola</label>
                    </div>
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('login') }}" class="small">Ti-ai amintit
                            parola?</a>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Resetare</button>
                </form>
            @else
                <form action="{{ route('password.email') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="form-floating">
                        <input type="email" class="form-control" name='email' id="email" placeholder="name@example.com"
                            required />
                        <label for="email">Email address</label>
                    </div>
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('login') }}" class="small">Ti-ai amintit
                            parola?</a>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Resetare</button>
                </form>
            @endif
        </div>
    </div>
@endsection
