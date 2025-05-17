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
                    <h3 class="mb-0">Categories</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg"></i> Add Category
                    </button>
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
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($categories)) {
                                                foreach ($categories as $row) { ?>
                                                    <tr>
                                                        <td><?= ucfirst($row['name']) ?></td>
                                                        <td style="width: 200px;">
                                                            <button type="button" class="btn btn-success btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editCategoryModal"
                                                                data-category-id="<?= $row['category_id'] ?>"
                                                                data-category-name="<?= ucfirst($row['name']) ?>"
                                                                >
                                                                Edit
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteCategoryModal"
                                                                data-category-id="<?= $row['category_id'] ?>"
                                                                data-category-name="<?= ucfirst($row['name']) ?>"
                                                                >
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                        <?php 
                                                }
                                            } else {
                                                echo '<tr><td class="text-danger" colspan="6">No Category</td></tr>';
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
<?= $this->include('components/category_modal/add') ?>
<?= $this->include('components/category_modal/update') ?>
<?= $this->include('components/category_modal/delete') ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editCategoryModal = document.getElementById('editCategoryModal');
        editCategoryModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.getAttribute('data-category-name');
            document.getElementById('editCategoryForm').action = `<?= base_url('categories/edit') ?>/${categoryId}`;
            document.getElementById('editCategoryName').value = categoryName;
        });

        const deleteCategoryModal = document.getElementById('deleteCategoryModal');
        deleteCategoryModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.getAttribute('data-category-name');
            document.getElementById('deleteCategoryForm').action = `<?= base_url('categories/delete') ?>/${categoryId}`;
            document.getElementById('deleteCategoryName').textContent = `"${categoryName}"`;
        })
    });
</script>

<?= $this->endSection() ?>
