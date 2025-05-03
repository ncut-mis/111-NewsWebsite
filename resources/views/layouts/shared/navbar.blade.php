<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home.index') }}">新聞網</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- 搜尋表單 --}}
            <form action="{{ route('search') }}" method="GET" class="d-flex ms-auto me-3">
                <input type="text" name="q" class="form-control me-2" placeholder="搜尋新聞..." value="{{ request('q') }}">
                <button type="submit" class="btn btn-outline-light">搜尋</button>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('favorites.index') }}" class="btn btn-outline-light me-3">收藏列表</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ url('/dashboard') }}">Dashboard</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">登出</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登入</a></li>
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">註冊</a></li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </div>
</nav>
