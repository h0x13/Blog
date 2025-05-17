<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-lte-toggle="sidebar"
                    href="#"
                    role="button"
                >
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a
                    href="#"
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <img
                        src="<?= base_url('assets/img/default-avatar.svg') ?>"
                        class="user-image rounded-circle shadow border"
                        alt="User Image"
                    />
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
                >
                    <!--begin::User Image-->
                    <li class="user-header text-bg-success">
                        <img
                            src="<?= base_url('assets/img/user2-160x160.jpg') ?>"
                            class="rounded-circle shadow"
                            alt="User Image"
                        />
                        <p>
                            Alexander Pierce
                            <small>Admin</small>
                        </p>
                    </li>
                    <!--end::User Image-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer d-flex justify-content-center">
                        <a href="#" class="btn btn-secondary ms-2"
                            ><i class="bi bi-moon-fill"></i></a
                        >
                        <a href="#" class="btn btn-success ms-2"
                            >Profile</a
                        >
                        <a
                            href="#"
                            class="btn btn-danger ms-2"
                            >Sign out</a
                        >
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
