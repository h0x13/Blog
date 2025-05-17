<?= $this->extend('templates/base') ?>

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
                    <h3 class="mb-0">Manage Blogs</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="blogs/add" class="btn btn-success">
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
                    if (isset($blogs)) {
                        foreach ($blogs as $blog) { ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 mb-sm-0">
                                <div class="card blog-card">
                                    <img src="<?= base_url('blogs/thumbnail/' . $blog['thumbnail']) ?>" alt="">
                                    <div class="card-body blog-body">
                                        <h5 class="card-title fw-bold mb-3">
                                            <?= esc($blog['title']) ?>
                                        </h5>
                                        <p class="card-text content"><?= get_introduction($blog['content']) ?></p>
                                        <p class="card-text author my-0"><b>Last Modified&colon;</b> <?= (new DateTime($blog['updated_at']))->format('F j, Y') ?></p>
                                        <p class="card-text last-modified my-0"><b>Author&colon;</b> <?= $blog['first_name'] . ' ' . $blog['middle_name'] . ' ' . $blog['last_name'] ?></p>
                                    </div>
                                    <div class="card-body d-flex">
                                        <a class="text-decoration-none" href="<?= base_url("blogs/edit/{$blog['slug']}") ?>">
                                            Edit
                                        </a>
                                        <a class="text-decoration-none ms-auto" href="<?= base_url("blogs/view/{$blog['slug']}") ?>">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                <?php 
                        }
                    } else {
                        echo '<tr><td class="text-danger" colspan="6">No users</td></tr>';
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
