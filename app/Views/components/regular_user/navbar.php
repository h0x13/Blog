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
                    <span>Alexander Pierce</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end rounded">
                    <ul class="list-group">
                        <li class="list-group-item border-bottom">
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= base_url('assets/img/user2-160x160.jpg') ?>"
                                    class="rounded-circle shadow me-2"
                                    alt="User Image"
                                    style="max-width: 40px;"
                                />
                                <div class="d-flex flex-column">
                                    <p class="fw-bold">
                                        Alexander Pierce
                                    </p>
                                    <small>
                                        alexander@gmail.com
                                    </small>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-person-circle me-2"></i> Profile
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#SwitchTheme" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-moon-fill me-2"></i> Switch theme
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-box-arrow-in-left me-2"></i> Sign out
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
