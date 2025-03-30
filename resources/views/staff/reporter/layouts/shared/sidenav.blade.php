<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="{{ route('staff.reporter.dashboard') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    主控台
                </a>
                <a class="nav-link" href="{{ route('staff.reporter.dashboard') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    新聞管理
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">登入身份:</div>
            {{ Auth::guard('staff')->user()->name }}
        </div>
    </nav>
</div>
