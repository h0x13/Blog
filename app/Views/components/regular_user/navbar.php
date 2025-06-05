<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body py-0">
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

        <div class="input-group rounded mx-5 my-2 position-relative">
          <input type="search" id="searchInput" class="form-control rounded" placeholder="Search Blog..." aria-label="Search" aria-describedby="search-addon"
            <?= isset($search_query)? "value=\"$search_query\"" : '' ?> />
          <span class="input-group-text text-bg-adaptive border-0" id="search-addon">
            <i class="bi bi-search"></i>
          </span>
          <div id="searchResults" class="position-absolute w-100 mt-1 bg-body rounded shadow-sm" style="display: none; z-index: 1000; max-height: 400px; overflow-y: auto; top: 100%;">
          </div>
        </div>

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <?php if(session()->get('isLoggedIn')): ?>
                <a
                    href="#"
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <img
                        src="<?= session()->get('image')? base_url('user-image/' . session()->get('image')) 
                            :  base_url('assets/img/default-avatar.svg') ?>"
                        class="user-image rounded-circle shadow border"
                        alt="User Image"
                    />
                    <span><?= session()->get('user_name') ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end rounded">
                    <ul class="list-group">
                        <li class="list-group-item border-bottom">
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= session()->get('image')? base_url('user-image/' . session()->get('image')) 
                                        :  base_url('assets/img/default-avatar.svg') ?>"
                                    class="rounded-circle shadow me-2"
                                    alt="User Image"
                                    style="max-width: 40px;"
                                />
                                <div class="d-flex flex-column">
                                    <p class="fw-bold">
                                        <?= session()->get('user_name') ?>
                                    </p>
                                    <small>
                                        <?= session()->get('email') ?>
                                    </small>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <a href="<?= base_url('profile') ?>" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-person-circle me-2"></i> Profile
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#SwitchTheme" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-moon-fill me-2"></i> Switch theme
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="<?= base_url('logout') ?>" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-box-arrow-in-left me-2"></i> Sign out
                            </a>
                        </li>
                    </ul>
                </div>
                <?php else: ?>
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
                    <span>Guest</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end rounded">
                    <ul class="list-group">
                        <li class="list-group-item border-bottom">
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= base_url('assets/img/default-avatar.svg') ?>"
                                    class="rounded-circle shadow me-2"
                                    alt="User Image"
                                    style="max-width: 40px;"
                                />
                                <div class="d-flex flex-column">
                                    <p class="fw-bold">
                                        Guest
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <a href="<?= base_url('sign-in') ?>" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Sign in
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#SwitchTheme" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-moon-fill me-2"></i> Switch theme
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="/" class="text-decoration-none text-adaptive d-block w-100">
                                <i class="bi bi-house me-2"></i> Home
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->

<style>
#searchResults {
    border: 1px solid var(--bs-border-color);
    width: 400px;
    right: 0;
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
}

#searchResults a {
    border-bottom: 1px solid var(--bs-border-color);
    text-decoration: none;
    color: inherit;
}

#searchResults a:last-child {
    border-bottom: none;
}

#searchResults a:hover {
    background-color: var(--bs-gray-100);
}

.search-result-item {
    display: flex;
    padding: 0.75rem;
    gap: 0.75rem;
}

.search-result-thumbnail {
    width: 120px;
    height: 68px;
    object-fit: cover;
    border-radius: 4px;
}

.search-result-content {
    flex: 1;
    min-width: 0;
}

.search-result-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: var(--bs-body-color);
}

.search-result-author {
    font-size: 0.875rem;
    color: var(--bs-gray-600);
    margin-bottom: 0.25rem;
}

.search-result-categories {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.search-result-category {
    font-size: 0.75rem;
    padding: 0.125rem 0.5rem;
    background-color: var(--bs-gray-100);
    border-radius: 4px;
    color: var(--bs-gray-600);
}

[data-bs-theme="dark"] #searchResults {
    background-color: var(--bs-dark);
    border-color: var(--bs-border-color);
}

[data-bs-theme="dark"] #searchResults a {
    color: var(--bs-body-color);
    border-bottom-color: var(--bs-border-color);
}

[data-bs-theme="dark"] #searchResults a:hover {
    background-color: rgb(19, 19, 19);
}

[data-bs-theme="dark"] .search-result-author {
    color: var(--bs-gray-400);
}

[data-bs-theme="dark"] .search-result-category {
    background-color:rgb(29, 29, 29);
    color: var(--bs-gray-400);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchAddon = document.getElementById('search-addon');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`<?= base_url('blogs/search') ?>?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(blogs => {
                    if (blogs.length === 0) {
                        searchResults.innerHTML = '<div class="p-2 text-center">No results found</div>';
                    } else {
                        searchResults.innerHTML = blogs.map(blog => `
                            <a href="<?= base_url('blogs/view/') ?>${blog.slug}" class="d-block">
                                <div class="search-result-item">
                                    <img src="${blog.thumbnail ? '<?= base_url('blogs/thumbnail/') ?>' + blog.thumbnail : '<?= base_url('assets/img/default-blog.jpg') ?>'}" 
                                         alt="${blog.title}" 
                                         class="search-result-thumbnail"
                                         onerror="this.src='<?= base_url('assets/img/default-blog.jpg') ?>'">
                                    <div class="search-result-content">
                                        <div class="search-result-title">${blog.title}</div>
                                        <div class="search-result-author">
                                            ${blog.first_name} ${blog.middle_name} ${blog.last_name}
                                        </div>
                                        <div class="search-result-categories">
                                            ${blog.categories ? blog.categories.map(category => `
                                                <span class="search-result-category">${category.name}</span>
                                            `).join('') : ''}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `).join('');
                    }
                    searchResults.style.display = 'block';
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.style.display = 'none';
                });
        }, 300);
    });

    // Handle Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.trim().length >= 2) {
            const firstResult = searchResults.querySelector('a');
            if (firstResult) {
                window.location.href = firstResult.href;
            }
        }
    });

    searchAddon.addEventListener('click', function(e) {
        const textInput = searchInput.value;
        if (textInput && textInput.length >= 2) {
            window.location.href = `/blogs/search-result?q=${textInput}`;
        }
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
