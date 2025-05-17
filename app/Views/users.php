<?= $this->extend('templates/base') ?>

<?= $this->section('title') ?>
Users
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
                    <h3 class="mb-0">Manage Users</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-lg"></i> Add New User
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
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Last Modified</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($users)) {
                                                foreach ($users as $row) { ?>
                                                    <tr>
                                                        <td><?= $row['first_name'] ?> <?= $row['middle_name'] ? "{$row['middle_name']} " : "" ?><?= $row['last_name'] ?></td>
                                                        <td><?= $row['email'] ?></td>
                                                        <td>
                                                            <span class='badge bg-<?= $row['role'] == 'admin' ? 'danger' : ($row['role'] == 'author' ? 'warning' : 'info') ?>'>
                                                                <?= ucfirst($row['role']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class='badge bg-<?= $row['is_enabled'] ? 'success' : 'secondary' ?>'>
                                                                <?= $row['is_enabled'] ? 'Active' : 'Inactive' ?>
                                                            </span>
                                                        </td>
                                                        <td><?= date('d-m-Y H:i:s', strtotime($row['updated_at'])) ?></td>
                                                        <td>
                                                            <!-- Edit Button -->
                                                            <button class="btn btn-sm btn-success edit-btn"
                                                                data-id="<?= $row['user_id'] ?>"
                                                                data-fname="<?= $row['first_name'] ?>"
                                                                data-lname="<?= $row['last_name'] ?>"
                                                                data-mname="<?= $row['middle_name'] ?>"
                                                                data-email="<?= $row['email'] ?>"
                                                                data-role="<?= $row['role'] ?>"
                                                                data-status="<?= $row['is_enabled'] ?>"
                                                                data-gender="<?= $row['gender'] ?>"
                                                                data-birthdate="<?= $row['birthdate'] ?>"
                                                                data-image="<?= $row['image'] ?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editUserModal"
                                                            >
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </button>

                                                            <!-- Delete Button -->
                                                            <button class="btn btn-sm btn-danger delete-btn" 
                                                                data-id="<?= $row['user_id'] ?>" 
                                                                data-name="<?= $row['first_name'] . ' ' . $row['last_name'] ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal">
                                                                <i class="bi bi-trash"></i> Delete
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
<?= $this->include('components/user_modal/add') ?>
<?= $this->include('components/user_modal/update') ?>
<?= $this->include('components/user_modal/delete') ?>
<?= $this->include('components/user_scripts') ?>
<?= $this->endSection() ?>
