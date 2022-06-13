@section('navbar-no-log')
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                StudBud
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Despre StudBud</a>
                    </li>
                </ul>
                <a class="ml-lg-2 my-2 my-lg-0 btn btn-outline-primary" href="/register">Sign up</a>
            </div>
        </div>
    </nav>
