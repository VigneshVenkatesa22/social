<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2 px-4">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            Mediumish
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left Side Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('articles.create') }}"><i class="bi bi-pencil-square"></i>
                        Write</a>
                </li>
            </ul>

            <!-- Search -->
            <form class="d-flex me-3" role="search">
                <input class="form-control form-control-sm me-2" type="search" placeholder="Search"
                    aria-label="Search">
            </form>

            <!-- Right Side: Profile, Username, Logout -->
            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        @if (auth()->user()->avatar_image)
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" width="32" height="32"
                                class="rounded-circle" style="object-fit: cover;">
                        @else
                            <i class="fas fa-user-circle fa-lg"></i>
                        @endif
                    </a>
                </li>
                <li class="nav-item me-3">
                    <span class="navbar-text">Vignesh</span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
