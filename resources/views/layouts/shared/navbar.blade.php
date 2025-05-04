<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home.index') }}">新聞網</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- 搜尋表單 --}}
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="GET" action="{{ route('search') }}">
                <div class="input-group">
                    <input class="form-control" type="text" name="q" placeholder="搜尋新聞..." aria-label="搜尋新聞..."
                           aria-describedby="btnNavbarSearch" value="{{ request('q') }}"/> <!-- 確保 value 正確 -->
                    <button class="btn btn-primary" id="btnNavbarSearch" type="submit">搜尋</button>
                </div>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('favorites.index') }}">收藏列表</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">會員中心</a></li>
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
