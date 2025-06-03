<?= $this->extend('templates/base') ?>

<?= $this->section('title') ?>
<?= isset($type) && $type === 'popular' ? 'Popular Blogs' : 'Recent Blogs' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!-- Category Chips -->
                <div class="col-12">
                    <div class="category-chips-container">
                        <div class="category-chips-scroll">
                            <a href="<?= base_url($type === 'popular' ? 'blogs/popular' : 'blogs') ?>" class="category-chip <?= !isset($current_category) ? 'active' : '' ?>">
                                All
                            </a>
                            <?php if(isset($categories) && !empty($categories)): ?>
                                <?php foreach($categories as $category): ?>
                                    <a href="<?= base_url("blogs/category/{$category['name']}" . ($type === 'popular' ? '?type=popular' : '')) ?>" 
                                       class="category-chip <?= (isset($current_category) && $current_category['category_id'] == $category['category_id']) ? 'active' : '' ?>">
                                        <?= esc($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button class="category-chips-arrow category-chips-arrow-left" onclick="scrollCategories('left')">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="category-chips-arrow category-chips-arrow-right" onclick="scrollCategories('right')">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <?= $this->include('blog_pages/message') ?>
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row bg-transparent">
                <?php
                    if (isset($blogs) && !empty($blogs)) {
                        foreach ($blogs as $blog) { ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 d-flex">
                                <div class="card blog-card w-100">
                                    <img src="<?= base_url('blogs/thumbnail/' . $blog['thumbnail']) ?>" alt="<?= esc($blog['title']) ?>">
                                    <div class="card-body blog-body">
                                        <h5 class="card-title fw-bold mb-3">
                                            <?= esc($blog['title']) ?>
                                        </h5>
                                        <p class="card-text content"><?= get_introduction($blog['content']) ?></p>
                                        <p class="card-text author my-0"><b>Last Modified&colon;</b> <?= (new DateTime($blog['updated_at']))->format('F j, Y') ?></p>
                                        <p class="card-text last-modified my-0"><b>Author&colon;</b> <?= $blog['first_name'] . ' ' . $blog['middle_name'] . ' ' . $blog['last_name'] ?></p>
                                        <?php if ($type === 'popular'): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-chat-dots me-1"></i><?= $blog['comment_count'] ?? 0 ?> comments
                                                    <i class="bi bi-heart ms-2 me-1"></i><?= $blog['reaction_count'] ?? 0 ?> reactions
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body d-flex">
                                        <a class="text-decoration-none mt-auto ms-auto" href="<?= base_url("blogs/view/{$blog['slug']}") ?>">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                <?php 
                        }
                    } else {
                        echo '<div class="col-12 text-center py-5">
                                <img src="' . base_url('assets/img/empty.png') . '" alt="No blogs found" class="mb-3" style="max-width: 200px;">
                                <p class="text-muted">No blogs found</p>
                            </div>';
                    }
                ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Blog pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url($type === 'popular' ? 'blogs/popular' : 'blogs') ?>?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($pager->getPageCount(), $currentPage + 2);

                            if ($startPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="' . base_url($type === 'popular' ? 'blogs/popular' : 'blogs') . '?page=1">1</a></li>';
                                if ($startPage > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }

                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url($type === 'popular' ? 'blogs/popular' : 'blogs') ?>?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor;

                            if ($endPage < $pager->getPageCount()) {
                                if ($endPage < $pager->getPageCount() - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="' . base_url($type === 'popular' ? 'blogs/popular' : 'blogs') . '?page=' . $pager->getPageCount() . '">' . $pager->getPageCount() . '</a></li>';
                            }
                            ?>

                            <?php if ($currentPage < $pager->getPageCount()): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url($type === 'popular' ? 'blogs/popular' : 'blogs') ?>?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->

<style>
    .category-chips-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 0.5rem 0;
        margin-bottom: 1rem;
    }

    .category-chips-scroll {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
        padding: 0.5rem 0;
        scroll-behavior: smooth;
    }

    .category-chips-scroll::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .category-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #f2f2f2;
        color: #0f0f0f;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #e5e5e5;
        cursor: pointer;
    }

    .category-chip:hover {
        background-color: #e5e5e5;
        color: #0f0f0f;
        text-decoration: none;
    }

    .category-chip.active {
        background-color: #0f0f0f;
        color: #ffffff;
        border-color: #0f0f0f;
    }

    .category-chips-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 1px solid #e5e5e5;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .category-chips-arrow:hover {
        background-color: #f2f2f2;
    }

    .category-chips-arrow-left {
        left: 0;
    }

    .category-chips-arrow-right {
        right: 0;
    }

    [data-bs-theme="dark"] .category-chip {
        background-color: #272727;
        color: #ffffff;
        border-color: #3f3f3f;
    }

    [data-bs-theme="dark"] .category-chip:hover {
        background-color: #3f3f3f;
    }

    [data-bs-theme="dark"] .category-chip.active {
        background-color: #ffffff;
        color: #0f0f0f;
        border-color: #ffffff;
    }

    [data-bs-theme="dark"] .category-chips-arrow {
        background-color: #0f0f0f;
        border-color: #3f3f3f;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    [data-bs-theme="dark"] .category-chips-arrow:hover {
        background-color: #272727;
    }

    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        color: var(--bs-primary);
        border: 1px solid var(--bs-border-color);
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }

    .pagination .page-item:not(.active) .page-link:hover {
        background-color: var(--bs-tertiary-bg);
        border-color: var(--bs-primary);
    }

    .pagination .page-item.disabled .page-link {
        color: var(--bs-secondary-color);
        pointer-events: none;
        background-color: var(--bs-tertiary-bg);
        border-color: var(--bs-border-color);
    }

    [data-bs-theme="dark"] .pagination .page-link {
        background-color: var(--bs-dark);
        border-color: var(--bs-border-color);
    }

    [data-bs-theme="dark"] .pagination .page-item:not(.active) .page-link:hover {
        background-color: var(--bs-tertiary-bg);
    }

    [data-bs-theme="dark"] .pagination .page-item.disabled .page-link {
        background-color: var(--bs-dark);
        border-color: var(--bs-border-color);
    }

    /* Blog Type Toggle Styles */
    .btn-group .btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-group .btn i {
        font-size: 1rem;
    }
</style>

<script>
function scrollCategories(direction) {
    const container = document.querySelector('.category-chips-scroll');
    const scrollAmount = 200; // Adjust this value to control scroll distance
    
    if (direction === 'left') {
        container.scrollLeft -= scrollAmount;
    } else {
        container.scrollLeft += scrollAmount;
    }
}

// Add scroll event listener to show/hide arrows
document.querySelector('.category-chips-scroll').addEventListener('scroll', function() {
    const container = this;
    const leftArrow = document.querySelector('.category-chips-arrow-left');
    const rightArrow = document.querySelector('.category-chips-arrow-right');
    
    // Show/hide left arrow
    if (container.scrollLeft > 0) {
        leftArrow.style.display = 'flex';
    } else {
        leftArrow.style.display = 'none';
    }
    
    // Show/hide right arrow
    if (container.scrollLeft < (container.scrollWidth - container.clientWidth)) {
        rightArrow.style.display = 'flex';
    } else {
        rightArrow.style.display = 'none';
    }
});

// Initial check for arrows
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.category-chips-scroll');
    const leftArrow = document.querySelector('.category-chips-arrow-left');
    const rightArrow = document.querySelector('.category-chips-arrow-right');
    
    // Hide left arrow initially
    leftArrow.style.display = 'none';
    
    // Show right arrow if content is scrollable
    if (container.scrollWidth > container.clientWidth) {
        rightArrow.style.display = 'flex';
    } else {
        rightArrow.style.display = 'none';
    }
});
</script>
<?= $this->endSection() ?>
