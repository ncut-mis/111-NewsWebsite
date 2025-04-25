<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">登入身份: {{ Auth::guard('staff')->user()->name }}</div>
            <div>
                <a href="#"></a>
                &middot;
                <a href="#"></a>
            </div>
        </div>
    </div>
</footer>
