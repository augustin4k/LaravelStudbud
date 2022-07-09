@extends('layouts.auth')

@push('customCss')
    <link rel="stylesheet" href="/css/header-profile.css">
@endpush
{{-- functii aparte --}}
@php
// concat name si surname
if (empty($_GET['id'])) {
    $_GET['id'] = auth()->user()->id;
}
$user_info = App\Models\User::select(Illuminate\Support\Facades\DB::raw('concat(name, " ", surname) as name'), 'id')
    ->where('id', $_GET['id'])
    ->limit(1)
    ->get()
    ->toArray()[0];
// relatia user-ului logat cu acest user
if (Auth::check()) {
    if ($_GET['id'] != auth()->user()->id) {
        $relationship = App\Models\Friends::where([['friend_id', $_GET['id']], ['user_id', auth()->user()->id]])
            ->get()
            ->merge(App\Models\Friends::where([['user_id', $_GET['id']], ['friend_id', auth()->user()->id]])->get())
            ->toArray();
        if (count($relationship) == 0) {
            $status = 'not_friends';
        } else {
            $relationship = $relationship[0];
            if ($relationship['blocked_by_id'] == null) {
                if ($relationship['active'] == 1) {
                    $status = 'friends';
                } else {
                    if ($relationship['user_id'] == auth()->user()->id) {
                        $status = 'invited_by_me';
                    } else {
                        $status = 'invite_me';
                    }
                }
            } else {
                if ($relationship['blocked_by_id'] == $_GET['id']) {
                    $status = 'block_me';
                } elseif ($relationship['blocked_by_id'] == auth()->user()->id) {
                    $status = 'blocked_by_me';
                }
            }
        }
    } else {
        $status = 'its_me';
    }
} else {
    $status = 0;
}
// prietenii pentru userul selectat
$friends = App\Models\Friends::select('user_id as prieten_id')
    ->where([['friend_id', $_GET['id']], ['active', 1], ['blocked_by_id', null]])
    ->get();
$friends = array_merge(
    $friends->toArray(),
    App\Models\Friends::select('friend_id as prieten_id')
        ->where([['user_id', $_GET['id']], ['active', 1], ['blocked_by_id', null]])
        ->get()
        ->toArray(),
);
// review-urile pentru user-ul selectat
$reviews = App\Models\posts_reviews::where([['for_user', $_GET['id']], ['type', 'reviews']])->count();
// nr de fisiere al user-ului
$compartments = App\Models\compartments::where('user_id', $_GET['id'])->get();
$files = 0;

foreach ($compartments as $key => $value) {
    if ($value->files()) {
        $files = $files + $value->files()->count();
    }
}
@endphp

@section('content-in-app-auth')
    <div class="profile-page" id="profile-page">
        <div class="grid-margin profile-header">
            <div class="cover">
                <div class="gray-shade"></div>
                <figure> <img src="https://cdn.wallpapersafari.com/41/16/QL5I0O.jpg" class="img-fluid" alt="">
                </figure>
                <div class="cover-body hstack justify-content-between">
                    <div class="d-sm-flex hstack d-block gap-2">
                        @if ($status == 'block_me')
                            <img class="profile-pic rounded" src="/img/no-avatar.png" alt="profile" />
                        @elseif ($status != 'block_me')
                            <a href="#modelId" data-bs-toggle="modal" data-bs-target="#modelId">
                                <img class="profile-pic rounded"
                                    src="@php
                                    echo App\Models\User::select('avatar_path')
                                        ->where('id', $_GET['id'])
                                        ->get()[0]->avatar_path;
                                @endphp"
                                    alt="profile" />
                            </a>
                            @push('modals')
                                <!-- Modal -->
                                <div class="modal fade" id="modelId" tabindex="-1" role="dialog"
                                    aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Avatar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img class="w-100"
                                                    src="@php
                                                    echo App\Models\User::select('avatar_path')
                                                        ->where('id', $_GET['id'])
                                                    ->get()[0]->avatar_path; @endphp"
                                                    alt="profile" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endpush
                        @endif
                        <div class='d-flex flex-column gap-2'>
                            <p class="w-auto text-truncate profile-name m-0 profile-name" data-bs-toggle="tooltip"
                                data-bs-placement="top" title='{{ $user_info['name'] }}'>
                                {{ $user_info['name'] }}
                            </p>
                            @if ($status == 'block_me')
                                <small class="fw-bold opacity-75">(User-ul te-a blocat)</small>
                            @endif
                        </div>
                    </div>
                    @if (Auth::check() && auth()->user()->id != $_GET['id'] && $status !== 'block_me')
                        <div class="hstack gap-2">
                            <a href='{{ route('chat', ['id' => $_GET['id']]) }}'
                                class="btn btn-primary position-relative">
                                <i class="bi bi-chat"></i>
                                <span class="d-none d-sm-inline text-truncate">
                                    Trimite un mesaj
                                </span>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </a>
                            <v-buttons-friends :user="{{ json_encode($user_info) }}" :status={{ json_encode($status) }}>
                            </v-buttons-friends>
                        </div>
                    @endif
                </div>
            </div>
            @if ($status != 'block_me')
                <nav class="header-links pb-0">
                    <div class="nav nav-tabs navbar-expand" id="nav-tab" role="tablist">
                        <a href='{{ route('profile-timeline', ['id' => $_GET['id']]) }}'
                            class="nav-link @if (!empty($page) && $page == 'timeline') {{ 'active' }} @endif"
                            id="nav-timeline-tab" type="button">
                            <div class="d-xl-inline d-none">
                                <i class="bi bi-book-fill"></i>
                                <span class="d-md-inline d-none">
                                    Timeline
                                </span>
                            </div>
                            <div class="d-xl-none d-inline">
                                <i class="bi bi-newspaper"></i>
                                <span class="d-md-inline d-none">
                                    Postari
                                </span>
                            </div>
                        </a>
                        <button class="nav-link d-md-none d-block" id="nav-despre-tab" type="button">
                            <i class="fa-solid fa-user"></i>
                            <span class="d-md-inline d-none">
                                Despre
                            </span>
                        </button>
                        <a href='{{ route('profile-friends', ['id' => $_GET['id']]) }}'
                            class="nav-link @if (!empty($page) && $page == 'friends') {{ 'active' }} @endif"
                            id="nav-prieteni-tab" type='button'>
                            <i class="fa-solid fa-user-group"></i>
                            <span class="d-md-inline d-none">
                                Prieteni
                            </span>
                            @if (count($friends) > 0)
                                <span class="badge rounded-pill bg-info">
                                    {{ count($friends) }}
                                </span>
                            @endif
                        </a>
                        <a href='{{ route('profile-reviews', ['id' => $_GET['id']]) }}' class="nav-link"
                            id="nav-recenzii-tab">
                            <i class="bi bi-star-fill"></i>
                            <span class="d-md-inline d-none">
                                Recenzii
                            </span>
                            @if ($reviews)
                                <span class="badge rounded-pill bg-info">
                                    {{ $reviews }}
                                </span>
                            @endif
                        </a>
                        <a href='/files' class="nav-link" id="nav-fisiere-tab" type="button">
                            <i class="bi bi-file-earmark-code-fill"></i>
                            <span class="d-md-inline d-none">
                                Fisiere
                            </span>
                            @if ($files)
                                <span class="badge rounded-pill bg-info">
                                    {{ $files }}
                                </span>
                            @endif
                        </a>
                    </div>
                </nav>
            @endif

        </div>
    </div>
    @if ($status != 'block_me')
        @yield('for-profile-content')
    @endif
@endsection
