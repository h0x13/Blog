<!--begin::Sidebar-->
<?php
function isActivePage($page) {
    $filename = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
    if ($page === $filename) {
        echo 'active';
    }
}
?>
<aside
    class="app-sidebar bg-body-secondary shadow"
>
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="#" class="brand-link d-flex justify-content-center align-items-center">
            <!--begin::Brand Text-->
            <span class="brand-text fw-bold">BlogHub</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a
                        href="/blogs"
                        class="nav-link <?php isActivePage('blogs') ?>">
                        <i class="nav-icon bi bi-house-door"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="/blogs/popular"
                        class="nav-link <?php isActivePage('popular') ?>">
                        <i class="nav-icon bi bi-graph-up"></i>
                        <p>Popular</p>
                    </a>
                </li>
                <?php if(session()->get('isLoggedIn')): ?>
                <li class="nav-item">
                    <a
                        href="/blogs/manage"
                        class="nav-link <?php isActivePage('manage') ?>">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Your Blogs</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="/audit-logs"
                        class="nav-link <?php isActivePage('audit-logs') ?>">
                        <i class="nav-icon bi bi-activity"></i>
                        <p>Activity Log</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
