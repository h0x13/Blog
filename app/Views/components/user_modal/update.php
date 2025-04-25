<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="id">
                    <input type="hidden" id="editCurrentImage" name="current_image">

                    <!-- Profile Image Section -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="<?= base_url('assets/img/default-avatar.svg') ?>" id="editImagePreview" class="rounded-circle border border-3 border-primary shadow-sm" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('editImage').click()">
                            <input type="file" class="form-control d-none" id="editImage" name="image" accept="image/*">
                            <button id="editUploadImageButton" type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 bg-primary rounded-circle px-2 py-1 shadow-sm" style="cursor: pointer; display: block;" onclick="document.getElementById('editImage').click()">
                                <i class="bi bi-camera-fill text-white"></i>
                            </button>
                            <button id="editRemoveImageButton" type="button" class="btn btn-danger btn-sm position-absolute bottom-0 end-0 rounded-circle shadow-sm" style="cursor: pointer; display: none;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <!-- Personal Information Card -->
                        <div class="col-12">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="editFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="editMiddleName" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="editMiddleName" name="middle_name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="editLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="editLastName" name="last_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editGender" class="form-label">Gender</label>
                                            <select class="form-select" id="editGender" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editBirthdate" class="form-label">Birthdate</label>
                                            <input type="date" class="form-control" id="editBirthdate" name="birthdate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information Card -->
                        <div class="col-12">
                            <div class="card shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Account Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="editEmail" name="email" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editPassword" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="editPassword" name="password">
                                                <button class="btn btn-secondary" type="button" id="toggleEditPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Leave blank to keep current password</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editRole" class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" id="editRole" name="role" required>
                                                <option value="admin">Admin</option>
                                                <option value="author">Author</option>
                                                <option value="viewer">Viewer</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select" id="editStatus" name="is_enabled" required>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>