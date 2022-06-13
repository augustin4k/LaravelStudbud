<div class="card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between bg-primary text-white">
        <p class="m-0 card-title fw-bold"> Despre </p>
    </div>
    <ul class="small list-group list-group-flush">
        <li class="list-group-item bg-light">
            <i class="bi bi-file-person"></i> <span class="card-text text-truncate"> Date personale
            </span>
        </li>
        <li class="text-secondary list-group-item d-flex align-items-center gap-1">
            <b>Tip user:</b>{{ $user_info->user_type }}
        </li>
        @if ($user_info->user_type != 'Universitate')
            <li class="text-secondary list-group-item"> <b>Prenume:</b> {{ $user_info->name }} </li>
            <li class="text-secondary list-group-item"> <b>Nume:</b> {{ $user_info->surname }} </li>
        @else
            <li class="text-secondary list-group-item"> <b>Denumire insitutie:</b>
                {{ $user_info->name . ' ' . $user_info->surname }} </li>
        @endif
        <li class="text-secondary list-group-item d-flex align-items-center gap-1"> <span class="text-truncate">
                <b>
                    @if ($user_info->user_type == 'Universitate')
                        Data fondarii:
                    @else
                        Data nasterii:
                    @endif
                </b> {{ $user_info->date_birth }}</span>

        </li>
        @if ($user_info->user_type == 'Universitate')
            <li class="text-secondary list-group-item">
                @if ($user_info->prezent_activity)
                    <b>Functionam pana in prezent</b>
                @else
                    <b>Data finala: </b> {{ $user_info->date_finish }}
                @endif
            </li>
        @endif
        @if (($user_info->user_type == 'Student' || $user_info->user_type == 'Profesor') && count($user_info->activities) > 0)
            <li class="list-group-item bg-light"> <i class="fa-solid fa-building-columns"></i>
                <span class="card-text text-truncate"> Activitati </span>
            </li>
            <li class="text-secondary list-group-item">
                <ol class="p-0 list-unstyled m-0">
                    @for ($i = 0; $i < count($user_info->activities); $i++)
                        <li>
                            <span class="text-truncate" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $user_info->activities[$i]->name_institution . ' (' . $user_info->activities[$i]->grade . ')' }}">
                                {{ $user_info->activities[$i]->name_institution . ' (' . $user_info->activities[$i]->grade . ')' }}
                            </span>
                            <p class="text-muted small">
                                {{ $user_info->activities[$i]->specialization .
                                    ' (' .
                                    date('Y', strtotime($user_info->activities[$i]->date_start)) .
                                    ' - ' }}
                                @if ($user_info->activities[$i]->prezent_activity)
                                    Prezent)
                                @else
                                    {{ date('Y', strtotime($user_info->activities[$i]->date_finish)) . ')' }}
                                @endif
                            </p>
                        </li>
                    @endfor
                </ol>
            </li>
        @endif
    </ul>
</div>
