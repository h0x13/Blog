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
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Your Blogs</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="<?= base_url('blogs/add') ?>" class="btn btn-success">
                        <i class="bi bi-plus-lg"></i> Add Blog Post
                    </a>
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
                                    <a class="text-decoration-none mt-auto" href="<?= base_url("blogs/edit/{$blog['slug']}") ?>">
                                        Edit
                                    </a>
                                    <a class="text-decoration-none mt-auto ms-auto" href="<?= base_url("blogs/view/{$blog['slug']}") ?>">
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
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->
<?= $this->endSection() ?>
