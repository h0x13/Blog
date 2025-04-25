<script>
const defaultAvatar = "<?= base_url('assets/img/default-avatar.svg') ?>";

document.addEventListener("DOMContentLoaded", function () {
    // Image Preview and Upload Functionality
    function setupImagePreview(inputId, previewId, uploadButtonId, removeButtonId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const uploadButton = document.getElementById(uploadButtonId);
        const removeButton = document.getElementById(removeButtonId);

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    uploadButton.style.display = 'none';
                    removeButton.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        removeButton.addEventListener('click', function(e) {
            e.preventDefault();
            input.value = '';
            preview.src = defaultAvatar;
            uploadButton.style.display = 'block';
            removeButton.style.display = 'none';
        });
    }

    // Password Toggle Functionality
    function setupPasswordToggle(inputId, toggleId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(toggleId);
        
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }

    // Form Validation and Submission
    function setupFormValidation(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });
        }
    }

    // Setup for Add User Modal
    setupImagePreview(
        'addImage',
        'addImagePreview',
        'addUploadImageButton',
        'addRemoveImageButton'
    );

    // Setup for Edit User Modal
    setupImagePreview(
        'editImage',
        'editImagePreview',
        'editUploadImageButton',
        'editRemoveImageButton'
    );

    // Setup password toggles
    setupPasswordToggle('addPassword', 'toggleAddPassword');
    setupPasswordToggle('editPassword', 'toggleEditPassword');

    // Setup form validation
    setupFormValidation('addUserForm');
    setupFormValidation('editUserForm');
    setupFormValidation('deleteUserForm');

    // Edit User Modal Setup
    const editUserModal = document.getElementById('editUserModal');
    if (editUserModal) {
        editUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-id');
            const firstName = button.getAttribute('data-fname');
            const middleName = button.getAttribute('data-mname') || '';
            const lastName = button.getAttribute('data-lname');
            const email = button.getAttribute('data-email');
            const role = button.getAttribute('data-role');
            const status = button.getAttribute('data-status');
            const gender = button.getAttribute('data-gender') || '';
            const birthdate = button.getAttribute('data-birthdate') || '';
            const image = button.getAttribute('data-image') || '';

            if (!userId) {
                console.error('User ID is missing');
                return;
            }

            // Set form action with route parameter
            const form = this.querySelector('form');
            form.action = `<?= base_url('users/edit') ?>/${userId}`;

            // Populate form fields
            document.getElementById('editUserId').value = userId;
            document.getElementById('editFirstName').value = firstName;
            document.getElementById('editMiddleName').value = middleName;
            document.getElementById('editLastName').value = lastName;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editStatus').value = status;
            document.getElementById('editGender').value = gender;
            document.getElementById('editBirthdate').value = birthdate;
            
            // Set current image
            const imagePreview = document.getElementById('editImagePreview');
            const currentImage = document.getElementById('editCurrentImage');
            if (image) {
                imagePreview.src = `<?= base_url('user-image/')?>${image}`;
                currentImage.value = image;
                document.getElementById('editUploadImageButton').style.display = 'none';
                document.getElementById('editRemoveImageButton').style.display = 'block';
            } else {
                imagePreview.src = defaultAvatar;
                currentImage.value = '';
                document.getElementById('editUploadImageButton').style.display = 'block';
                document.getElementById('editRemoveImageButton').style.display = 'none';
            }
        });
    }

    // Delete User Modal Setup
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-id');
            const userName = button.getAttribute('data-name');

            if (!userId) {
                console.error('User ID is missing');
                return;
            }

            // Set form action with route parameter
            const form = this.querySelector('form');
            form.action = `<?= base_url('users/delete') ?>/${userId}`;
            document.getElementById('deleteUserId').value = userId;
            document.querySelector('#deleteModal .modal-body p').textContent = 
                `Are you sure you want to delete the user "${userName}"? This action cannot be undone.`;
        });
    }
});
</script>
