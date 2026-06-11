<nav class="navbar d-none d-md-flex navbar-expand-lg navbar-dark bg-[#171a21] border-bottom border-[#1b2838] sticky-top" style="z-index: 1050;">
    <div class="container">
        <a class="navbar-brand font-bold text-[#66c0f4]" href="{{ route('home') }}">PlayMart</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Store</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('friends.*') ? 'active' : '' }}" href="{{ route('friends.index') }}">Teman</a>
                </li>
                @if(Auth::user()->is_admin)
                <li class="nav-item">
                    <a class="nav-link text-info font-bold" href="{{ route('admin.dashboard') }}">🛡 Admin Panel</a>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end bg-[#171a21] border-[#1b2838] shadow-lg">
                        <li><a class="dropdown-item text-white hover:bg-[#1b2838]" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider border-[#1b2838]"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
