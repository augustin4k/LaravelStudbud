@extends('layouts.NonAuth')

@section('content-in-app')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                @if ($errors->any())
                    {{-- pentru erori validarea php --}}
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <v-contact csrf-token='{{ csrf_token() }}' name-route='{{ route('contact-submit') }}'></v-contact>
            </div>
        </div>
    </div>
@endsection
