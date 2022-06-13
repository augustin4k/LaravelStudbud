@extends('pages.authentificated.profile')

@section('for-profile-content')
    <div class="row">
        <div class="left-wrapper mb-3 d-none d-md-block col-md-4">
            <div class="row sticky-top">
                <div class="col-md-12 grid-margin">
                </div>
                <div class="col-md-12 grid-margin">
                    <div class="accordion shadow-sm" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed bg-info text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <p class="m-0"><b>Prieteni comuni</b>
                                        <span class="badge rounded-pill bg-secondary">33</span>
                                    </p>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="list-group list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center ">
                                        <div class="d-flex align-items-center"> <img class="img-xs rounded me-1"
                                                src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="">
                                            <div class="d-flex flex-column"> <b>Cojocaru Augustin</b>
                                                <p class="text-muted small">12 prieteni comuni
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="btn-group">
                                                <button class="btn btn-light" type="button" id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i
                                                        class="fa-solid fa-ellipsis"></i> </button>
                                                <div class="dropdown-menu dropdown-menu-right shadow p-2"> <a
                                                        class="dropdown-item d-flex gap-1 align-items-center" href="#"> <i
                                                            class="bi bi-window-dock text-primary"></i>Visit profile </a> <a
                                                        class="dropdown-item d-flex gap-1 align-items-center" href="#"> <i
                                                            class="bi bi-chat text-primary"></i>Scrie un mesaj </a> <a
                                                        class="dropdown-item d-flex gap-1 align-items-center" href="#"><i
                                                            class="text-primary bi bi-emoji-frown"> </i>Unfollow</a> <a
                                                        class="dropdown-item d-flex gap-1 align-items-center" href="#"> <i
                                                            class="bi bi-person-x-fill text-primary"></i> Delete from
                                                        friends</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 right-wrapper">
        </div>
    </div>
@endsection
