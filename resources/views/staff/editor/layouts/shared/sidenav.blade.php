<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="{{ route('staff.editor.dashboard') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    主控台
                </a>
                <a class="nav-link" href="{{ route('staff.editor.review') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    待審核
                </a>
                <a class="nav-link" href="{{ route('staff.editor.published') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    已上線
                </a>
                <a class="nav-link" href="{{ route('staff.editor.return1') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-times"></i>
                    </div>
                    已退回
                </a>
                <a class="nav-link" href="{{ route('staff.editor.removed') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    已下架
                </a>
                <a class="nav-link" href="{{ route('staff.editor.categories.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    類別管理
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">登入身份:</div>
            {{ Auth::guard('staff')->user()->name }}
        </div>
    </nav>
</div>
