<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('staff.reporter.dashboard') }}">記者後台</a>
    <!-- Navbar Buttons-->
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.reporter.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> 所有新聞
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.reporter.news.writing') }}">
                <i class="fas fa-edit"></i> 撰稿中
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.reporter.news.review') }}">
                <i class="fas fa-clock"></i> 待審核
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.reporter.news.published') }}">
                <i class="fas fa-check"></i> 已上線
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.reporter.news.return') }}">
                <i class="fas fa-times"></i> 被退回
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.reporter.news.removed') }}">
                <i class="fas fa-archive"></i> 已下架
            </a>
        </li>
    </ul>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="GET" action="{{ route('staff.reporter.news.search') }}">
        <div class="input-group">
            <input class="form-control" type="text" name="query" placeholder="Search for..." aria-label="Search for..."
                   aria-describedby="btnNavbarSearch" value="{{ request('query') }}"/> <!-- 確保 value 正確 -->
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="nav-link">{{ Auth::guard('staff')->user()->name }}</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.login') }}">登出</a>
        </li>
    </ul>
</nav>
