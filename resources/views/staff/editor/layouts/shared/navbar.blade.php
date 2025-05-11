<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('staff.editor.dashboard') }}">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" style="height: 40px;">
    </a>
    <!-- Navbar Links-->
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.editor.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> 主控台
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.editor.review') }}">
                <i class="fas fa-tachometer-alt"></i> 待審核
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.editor.published') }}">
                <i class="fas fa-tachometer-alt"></i> 已上線
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.editor.return1') }}">
                <i class="fas fa-times"></i> 已退回
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.editor.removed') }}">
                <i class="fas fa-tachometer-alt"></i> 已下架
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.editor.categories.index') }}">
                <i class="fas fa-list"></i> 類別管理
            </a>
        </li>
    </ul>
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="GET" action="{{ route('staff.editor.search') }}">
        <div class="input-group">
            <input class="form-control" type="text" name="query" placeholder="Search for..." aria-label="Search for..."
                   aria-describedby="btnNavbarSearch" value="{{ request('query') }}"/> <!-- 確保 value 正確 -->
            <button class="btn btn-primary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- User Info and Logout-->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="nav-link">{{ Auth::guard('staff')->user()->name }}</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('staff.login') }}">登出</a>
        </li>
    </ul>
</nav>
