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
        <a href="/" class="brand-link d-flex justify-content-center align-items-center">
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
                        href="/dashboard"
                        class="nav-link <?php isActivePage('dashboard') ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="/users"
                        class="nav-link <?php isActivePage('users') ?>">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Manage Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="/categories"
                        class="nav-link <?php isActivePage('categories') ?>">
                        <i class="nav-icon bi bi-tags"></i>
                        <p>Manage Categories</p>
                    </a>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
