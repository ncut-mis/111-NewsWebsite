<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('staff.editor.dashboard') }}">主編後台</a>
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
