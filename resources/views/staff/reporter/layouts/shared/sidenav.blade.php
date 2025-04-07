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
                <a class="nav-link" href="{{ route('staff.reporter.news.writing') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    撰稿中
                </a>
                <a class="nav-link" href="{{ route('staff.reporter.news.review') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    待審核
                </a>
                <a class="nav-link" href="{{ route('staff.reporter.news.published') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    已上線
                </a>
                <a class="nav-link" href="{{ route('staff.reporter.news.return') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-times"></i>
                    </div>
                    被退回
                </a>
                <a class="nav-link" href="{{ route('staff.reporter.news.removed') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-archive"></i>
                    </div>
                    已下架
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">登入身份:</div>
            {{ Auth::guard('staff')->user()->name }}
        </div>
    </nav>
</div>
