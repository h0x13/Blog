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
                    <h3 class="mb-0">Blogs</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Blog Post</button>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">

        <!-- Message Display -->
        <?php if (session()->getFlashdata('message')): ?>
            <div class="container-fluid">
                <div class="alert alert-<?= session()->getFlashdata('message_type') ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="usersTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Author</th>
                                            <th>Visibility</th>
                                            <th>Last Modified</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($blogs)) {
                                                foreach ($blogs as $row) { ?>
                                                    <?php $author = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] ?>
                                                    <tr>
                                                        <td><?= $row['title'] ?></td>
                                                        <td><?= $row['content'] ?></td>
                                                        <td><?= $author ?></td>
                                                        <td>
                                                            <span class='badge bg-<?= $row['visibility'] !== 'private' ? 'success' : 'secondary' ?>'>
                                                                <?= $row['visibility'] ?>
                                                            </span>
                                                        </td>
                                                        <td><?= date('d-m-Y H:i:s', strtotime($row['updated_at'])) ?></td>
                                                        <td class="text-nowrap">
                                                            <button type="button" class="btn btn-secondary btn-sm">
                                                                <i class="bi bi-gear"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                        <?php 
                                                }
                                            } else {
                                                echo '<tr><td class="text-danger" colspan="6">No users</td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->
<?= $this->endSection() ?>
