@extends('pages.authentificated.profile')

@section('for-profile-content')
    <div class="row">
        <div class="left-wrapper col-12 col-md-4 d-none d-md-block">
            @include('components.info-card')
        </div>
        <div class="right-wrapper col-12 col-md-8">
            @php
                if (Auth::check() && empty($_GET['id'])) {
                    $selected_user_id = auth()->user()->id;
                } elseif (!empty($_GET['id'])) {
                    $selected_user_id = $_GET['id'];
                }
            @endphp
            <v-posts-reviews type='post' :selected_user_id='{{ $selected_user_id }}'
                @if (Auth::check()) :my_id='{{ auth()->user()->id }}'
            @else
                :my_id = '{{ 0 }}' @endif>
            </v-posts-reviews>
        </div>
    </div>
@endsection
