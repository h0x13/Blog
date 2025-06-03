<?= $this->extend('templates/regular_user/base') ?>

<?= $this->section('title') ?>
Blogs
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
                            <a href="<?= base_url('/blogs') ?>" class="category-chip <?= !isset($current_category) ? 'active' : '' ?>">
                                All
                            </a>
                            <?php if(isset($categories) && !empty($categories)): ?>
                                <?php foreach($categories as $category): ?>
                                    <a href="<?= base_url("blogs/category/{$category['name']}") ?>" 
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
                                    <h5 class="card-title fw-bold">
                                        <?= esc($blog['title']) ?>
                                    </h5>
                                    <p class="card-text content"><?= get_introduction($blog['content']) ?></p>
                                    <div class="mt-auto">
                                        <p class="card-text author my-0"><b>Last Modified:</b> <?= (new DateTime($blog['updated_at']))->format('F j, Y') ?></p>
                                        <p class="card-text last-modified my-0"><b>Author:</b> <?= $blog['first_name'] . ' ' . $blog['middle_name'] . ' ' . $blog['last_name'] ?></p>
                                    </div>
                                </div>
                                <div class="card-body d-flex pt-0">
                                    <a class="text-decoration-none mt-top ms-auto" href="<?= base_url("blogs/view/{$blog['slug']}") ?>">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php 
                    }
                } else {
                    echo '<div class="col-12 text-center"><p class="text-muted">No blogs found</p></div>';
                }
                ?>
            </div>

            <!-- Add this before the closing </div> of the main container -->
            <?php if (isset($currentPage) && isset($totalPages)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Blog pagination">
                        <ul class="pagination justify-content-center">
                            <?php if (isset($hasPrevPage) && $hasPrevPage): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Previous</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo; Previous</span>
                                </li>
                            <?php endif; ?>

                            <?php if (isset($hasNextPage) && $hasNextPage): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">Next &raquo;</span>
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
