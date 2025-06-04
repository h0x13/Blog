<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url('users/add') ?>" enctype="multipart/form-data" id="addUserForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_user">

                    <!-- Profile Image Section -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="<?= base_url('assets/img/default-avatar.svg') ?>" id="addImagePreview" class="rounded-circle border border-3 border-success shadow-sm" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('addImage').click()">
                            <input type="file" class="form-control d-none" id="addImage" name="image" accept="image/*">
                            <button id="addUploadImageButton" type="button" class="btn btn-success btn-sm position-absolute bottom-0 end-0 bg-success rounded-circle px-2 py-1 shadow-sm" style="cursor: pointer; display: block;" onclick="document.getElementById('addImage').click()">
                                <i class="bi bi-camera-fill text-white"></i>
                            </button>
                            <button id="addRemoveImageButton" type="button" class="btn btn-danger btn-sm position-absolute bottom-0 end-0 rounded-circle shadow-sm" style="cursor: pointer; display: none;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <!-- Personal Information Card -->
                        <div class="col-12">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="addFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('first_name') ? 'is-invalid' : '' ?>" id="addFirstName" name="first_name" value="<?= old('first_name') ?>" required>
                                            <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="addMiddleName" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('middle_name') ? 'is-invalid' : '' ?>" id="addMiddleName" name="middle_name" value="<?= old('middle_name') ?>">
                                            <?php if (isset($validation) && $validation->hasError('middle_name')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('middle_name') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="addLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('last_name') ? 'is-invalid' : '' ?>" id="addLastName" name="last_name" value="<?= old('last_name') ?>" required>
                                            <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="addGender" class="form-label">Gender</label>
                                            <select class="form-select <?= isset($validation) && $validation->hasError('gender') ? 'is-invalid' : '' ?>" id="addGender" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                                                <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                                                <option value="Other" <?= old('gender') == 'Other' ? 'selected' : '' ?>>Other</option>
                                            </select>
                                            <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('gender') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="addBirthdate" class="form-label">Birthdate</label>
                                            <input type="date" class="form-control <?= isset($validation) && $validation->hasError('birthdate') ? 'is-invalid' : '' ?>" id="addBirthdate" name="birthdate" value="<?= old('birthdate') ?>">
                                            <?php if (isset($validation) && $validation->hasError('birthdate')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('birthdate') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information Card -->
                        <div class="col-12">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Account Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="addEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" id="addEmail" name="email" value="<?= old('email') ?>" required>
                                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="addPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" id="addPassword" name="password" required>
                                                <button class="btn btn-secondary" type="button" id="toggleAddPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                                            <?php endif; ?>
                                            <div class="form-text">Minimum 8 characters</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="addRole" class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : '' ?>" id="addRole" name="role" required>
                                                <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                <option value="user" <?= old('role') == 'user' ? 'selected' : '' ?>>User</option>
                                            </select>
                                            <?php if (isset($validation) && $validation->hasError('role')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="addStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select <?= isset($validation) && $validation->hasError('is_enabled') ? 'is-invalid' : '' ?>" id="addStatus" name="is_enabled" required>
                                                <option value="1" <?= old('is_enabled') == '1' ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= old('is_enabled') == '0' ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                            <?php if (isset($validation) && $validation->hasError('is_enabled')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('is_enabled') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>
