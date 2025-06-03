<?= $this->extend('templates/base') ?>

<?= $this->section('title') ?>
Profile
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
                <div class="col-12">
                    <h1 class="mb-0">Profile</h1>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-<?= session('message_type') ?> alert-dismissible fade show" role="alert">
                            <?= session('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle me-2"></i>Edit Profile
                            </h5>
                        </div>
                        <form method="POST" action="<?= base_url('profile/update') ?>" enctype="multipart/form-data" id="profileForm">
                            <div class="card-body">
                                <?= csrf_field() ?>
                                <input type="hidden" id="currentImage" name="current_image" value="<?= isset($user['image']) ? $user['image'] : '' ?>">

                                <!-- Profile Image Section -->
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        <img src="<?= isset($user['image']) && !empty($user['image']) ? base_url('user-image/' . $user['image']) : base_url('assets/img/default-avatar.svg') ?>" 
                                             id="imagePreview" 
                                             class="rounded-circle border border-3 border-primary shadow-sm" 
                                             style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;" 
                                             onclick="document.getElementById('profileImage').click()">
                                        <input type="file" class="form-control d-none" id="profileImage" name="image" accept="image/*">
                                        <button id="uploadImageButton" type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 bg-primary rounded-circle px-2 py-1 shadow-sm" style="cursor: pointer;" onclick="document.getElementById('profileImage').click()">
                                            <i class="bi bi-camera-fill text-white"></i>
                                        </button>
                                        <?php if (isset($user['image']) && !empty($user['image'])): ?>
                                        <button id="removeImageButton" type="button" class="btn btn-danger btn-sm position-absolute bottom-0 start-0 rounded-circle shadow-sm" style="cursor: pointer;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Click to change profile picture</p>
                                </div>

                                <div class="row g-3 mt-4">
                                    <!-- Personal Information Card -->
                                    <div class="col-12">
                                        <div class="card shadow-sm mb-3">
                                            <div class="card-header text-bg-adaptive">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-person me-2"></i>Personal Information
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('first_name') ? 'is-invalid' : '' ?>" 
                                                               id="firstName" name="first_name" 
                                                               value="<?= old('first_name', $user['first_name'] ?? '') ?>" required>
                                                        <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                                                            <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="middleName" class="form-label">Middle Name</label>
                                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('middle_name') ? 'is-invalid' : '' ?>" 
                                                               id="middleName" name="middle_name" 
                                                               value="<?= old('middle_name', $user['middle_name'] ?? '') ?>">
                                                        <?php if (isset($validation) && $validation->hasError('middle_name')): ?>
                                                            <div class="invalid-feedback"><?= $validation->getError('middle_name') ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('last_name') ? 'is-invalid' : '' ?>" 
                                                               id="lastName" name="last_name" 
                                                               value="<?= old('last_name', $user['last_name'] ?? '') ?>" required>
                                                        <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                                                            <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="gender" class="form-label">Gender</label>
                                                        <select class="form-select <?= isset($validation) && $validation->hasError('gender') ? 'is-invalid' : '' ?>" 
                                                                id="gender" name="gender">
                                                            <option value="">Select Gender</option>
                                                            <option value="male" <?= old('gender', $user['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
                                                            <option value="female" <?= old('gender', $user['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
                                                            <option value="other" <?= old('gender', $user['gender'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                        </select>
                                                        <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                                            <div class="invalid-feedback"><?= $validation->getError('gender') ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="birthdate" class="form-label">Birthdate</label>
                                                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('birthdate') ? 'is-invalid' : '' ?>" 
                                                               id="birthdate" name="birthdate" 
                                                               value="<?= old('birthdate', $user['birthdate'] ?? '') ?>">
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
                                            <div class="card-header text-bg-adaptive">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-envelope me-2"></i>Account Information
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                                               id="email" name="email" 
                                                               value="<?= old('email', $user['email'] ?? '') ?>" required>
                                                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                                                            <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password Change Card -->
                                    <div class="col-12">
                                        <div class="card shadow-sm mb-3">
                                            <div class="card-header text-bg-adaptive">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-lock me-2"></i>Change Password
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="currentPassword" class="form-label">Current Password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control <?= isset($validation) && $validation->hasError('current_password') ? 'is-invalid' : '' ?>" 
                                                                   id="currentPassword" name="current_password">
                                                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <?php if (isset($validation) && $validation->hasError('current_password')): ?>
                                                                <div class="invalid-feedback"><?= $validation->getError('current_password') ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="newPassword" class="form-label">New Password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control <?= isset($validation) && $validation->hasError('new_password') ? 'is-invalid' : '' ?>" 
                                                                   id="newPassword" name="new_password">
                                                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <?php if (isset($validation) && $validation->hasError('new_password')): ?>
                                                                <div class="invalid-feedback"><?= $validation->getError('new_password') ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Leave password fields blank if you don't want to change your password
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-bg-adaptive">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i>Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const profileImage = document.getElementById('profileImage');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageButton = document.getElementById('removeImageButton');
    
    profileImage.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                // Show remove button after selecting new image
                if (removeImageButton) {
                    removeImageButton.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    if (removeImageButton) {
        removeImageButton.addEventListener('click', function() {
            imagePreview.src = '<?= base_url('assets/img/default-avatar.svg') ?>';
            profileImage.value = '';
            this.style.display = 'none';
            // Add hidden input to indicate image removal
            const removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_image';
            removeInput.value = '1';
            document.getElementById('profileForm').appendChild(removeInput);
        });
    }

    // Password toggle functionality
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const currentPassword = document.getElementById('currentPassword');
    const newPassword = document.getElementById('newPassword');

    toggleCurrentPassword.addEventListener('click', function() {
        const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        currentPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    toggleNewPassword.addEventListener('click', function() {
        const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        newPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    // Form validation
    const form = document.getElementById('profileForm');
    form.addEventListener('submit', function(e) {
        const currentPass = currentPassword.value;
        const newPass = newPassword.value;
        
        // If either password field has value, both should be filled
        if ((currentPass && !newPass) || (!currentPass && newPass)) {
            e.preventDefault();
            alert('Please fill both current password and new password fields to change password.');
            return false;
        }
    });
});
</script>

<?= $this->endSection() ?>
