@extends('layouts.NonAuth')

@section('content-in-app')
    <header class="p-5 mb-0 bg-primary text-white">
        <div class="container">
            <i class="fa fa-graduation-cap fa-4x" aria-hidden="true"></i>
            <h1 class="display-3">Bun venit pe StudBud!</h1>
            <p class="lead">
                Mai jos iti vom explica ce este StudBud si la ce te ajuta.
            </p>
            <a class="btn btn-lg btn-light" href="#about">Sa incepem!</a>
        </div>
    </header>
    <section class="about-section p-5 pb-0" id="about">
        <div class="container">
            <div class="">
                <div class="mb-4">
                    <div class="about-text go-to">
                        <h1 class="display-4">Despre noi</h1>
                        <p class="lead">
                            Aici ai parte de o gama larga de a serviciilor tipice unei
                            retele de socializare. Aici iti poti gasi colegii de facultate,
                            universitate, profesorii, studentii, si chiar si institutia din
                            care faci parte. Puteti comunica, sa va faceti prieteni, sa va
                            schimbati cu posturi interesante si chiar sa lasati recenzii sau
                            sa le cititi cele lasate de altii pentru a-ti usura alegerea.
                            Toate sunt aici!
                        </p>
                    </div>
                </div>
                <v-features></v-features>
            </div>
        </div>
    </section>
@endsection
