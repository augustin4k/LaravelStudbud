@extends('pages.authentificated.profile')

@section('for-profile-content')
    <v-friends @if ($user_login) :me='{{ json_encode(auth()->user()) }}' @endif
        :user_login="{{ $user_login }}" :friends='{{ json_encode($friends) }}' :im_user='{{ $my_profile }}'
        :users='{{ json_encode($users) }}'>
    </v-friends>
@endsection
