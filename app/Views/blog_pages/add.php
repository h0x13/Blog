<?= $this->extend('templates/regular_user/base') ?>

<?= $this->section('title') ?>Create New Blog<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('plugins/summernote/summernote-bs5.css') ?>">
<script src="<?= base_url('plugins/summernote/summernote-bs5.js') ?>"></script>
<style>
    .form-section {
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--bs-border-color);
        padding-bottom: 1.5rem;
    }
    .form-section:last-child {
        border-bottom: none;
    }
    .section-header {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        font-weight: 500;
        color: var(--bs-secondary-color);
    }
    .action-buttons {
        padding-top: 1rem;
    }
    .visibility-options {
        padding: 1rem;
        background-color: var(--bs-tertiary-bg);
        border-radius: var(--bs-border-radius);
    }
    .thumbnail-preview {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
        border-radius: var(--bs-border-radius);
        border: 1px solid var(--bs-border-color);
        display: none;
    }
    .thumbnail-preview-container {
        position: relative;
        display: inline-block;
    }
    .remove-thumbnail {
        position: absolute;
        top: 10px;
        right: 1px;
        background: rgba(var(--bs-body-color-rgb), 0.1);
        border-radius: 50%;
        cursor: pointer;
        padding: 0 4px;
        display: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Create New Blog</h2>
                        <a href="<?= previous_url() ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Blogs
                        </a>
                    </div>
                </div>
            </div>
            
            <?= $this->include('blog_pages/message') ?>
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?= base_url('blogs/add') ?>" method="post" enctype="multipart/form-data" >
                        <?= csrf_field() ?>
                        
                        <div class="form-section">
                            <div class="section-header">
                                <i class="bi bi-pencil-square"></i> Basic Information
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Title</label>
                                <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= old('title') ?>" placeholder="Enter a descriptive title" required>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="section-header">
                                <i class="bi bi-file-richtext"></i> Content
                            </div>
                            <div class="mb-3">
                                <textarea id="blogContent" name="content"><?= old('content') ?></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-header">
                                <i class="bi bi-image"></i> Thumbnail
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control" id="thumbnailInput" name="thumbnail" accept="image/*">
                                <input type="hidden" name="old_thumbnail" value="<?= old('thumbnail') ?>">
                                
                                <div class="thumbnail-preview-container mt-2">
                                    <img id="thumbnailPreview" class="thumbnail-preview" 
                                         src="<?= old('thumbnail') ? base_url('uploads/thumbnails/' . old('thumbnail')) : '' ?>" 
                                         <?= old('thumbnail') ? 'style="display: block;"' : '' ?>>
                                    <div class="remove-thumbnail" id="removeThumbnail" <?= old('thumbnail') ? 'style="display: block;"' : '' ?>>
                                        <i class="bi bi-x-circle text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="section-header">
                                        <i class="bi bi-eye"></i> Visibility
                                    </div>
                                    <div class="visibility-options">
                                        <div class="form-check form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="visibility" id="privateRadioBtn" value="private" <?= old('visibility') != 'public' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="privateRadioBtn">
                                                <i class="bi bi-lock-fill text-danger"></i> Private
                                                <small class="text-muted d-block">Only you can see this blog</small>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="visibility" id="publicRadioBtn" value="public" <?= old('visibility') == 'public' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="publicRadioBtn">
                                                <i class="bi bi-globe text-success"></i> Public
                                                <small class="text-muted d-block">Anyone can view this blog</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="section-header d-flex justify-content-between align-items-center">
                                        <div><i class="bi bi-tags"></i> Categories</div>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                            <i class="bi bi-plus"></i> Add Category
                                        </button>
                                    </div>
                                    <div id="categoryList" class="mt-2">
                                        <div class="text-muted small fst-italic">No categories selected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="action-buttons d-flex justify-content-end border-top pt-3">
                            <button type="button" id="previewBtn" class="btn btn-success me-2">
                                <i class="bi bi-eye"></i> Preview
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="categoryModalLabel">
                    <i class="bi bi-tag"></i> Add Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addCategorySelect" class="form-label">Select Category</label>
                    <select id="addCategorySelect" class="form-select">
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="addCategoryBtn" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="bi bi-plus"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">Blog Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4" id="previewThumbnailContainer">
                    <img id="previewThumbnail" class="img-fluid rounded" style="max-height: 300px; display: none;" src="" alt="">
                </div>
                <h1 id="previewTitle" class="mb-4"></h1>
                <div id="previewContent" class="border-bottom pb-4 mb-4"></div>
                <div class="d-flex">
                    <div id="previewVisibility" class="me-3"></div>
                    <div id="previewCategories"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?php
    // Create a map: [category_id => name]
    $categoryMap = array_column($categories, 'name', 'category_id');

    // Prepare the old selected categories in a simple loop
    $oldSelectedCategories = [];

    if (old('categories')) {
        foreach (old('categories') as $catId) {
            if (isset($categoryMap[$catId])) {
                $oldSelectedCategories[] = [
                    'id' => $catId,
                    'content' => $categoryMap[$catId]
                ];
            }
        }
    }
?>

<script>
    $(document).ready(function() {
        // Initialize Summernote with more options
        const $summernote = $('#blogContent').summernote({
            placeholder: 'Start writing your blog post here...',
            toolbar: [
              // Example toolbar configuration without font family
              ['style', ['style']],
              ['font', ['bold', 'italic', 'underline', 'clear']], // no 'fontname' here
              ['fontsize', ['fontsize']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['height', ['height']],
              ['insert', ['link', 'picture', 'video']],
              ['view', ['fullscreen', 'codeview', 'help']]
            ],
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        sendFile(files[i]);
                    }
                },
            }
        });

        function sendFile(file) {
            var data = new FormData();
            data.append("file", file);

            $.ajax({
                url: '/blogs/upload_image',
                method: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function(response) {
                    $summernote.summernote('insertImage', response.url);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  console.error('Upload failed:', textStatus, errorThrown);
                }
            });
        }

        // Thumbnail preview functionality
        const thumbnailInput = document.getElementById('thumbnailInput');
        const thumbnailPreview = document.getElementById('thumbnailPreview');
        const removeThumbnail = document.getElementById('removeThumbnail');
        let thumbnailFile = null;

        // Show image preview when file is selected
        thumbnailInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                thumbnailFile = this.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    thumbnailPreview.src = e.target.result;
                    thumbnailPreview.style.display = 'block';
                    removeThumbnail.style.display = 'block';
                    // Clear the old_thumbnail value since we're uploading a new one
                    document.querySelector('input[name="old_thumbnail"]').value = '';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Remove thumbnail
        removeThumbnail.addEventListener('click', function() {
            thumbnailPreview.src = '';
            thumbnailPreview.style.display = 'none';
            removeThumbnail.style.display = 'none';
            thumbnailInput.value = '';
            document.querySelector('input[name="old_thumbnail"]').value = '';
            thumbnailFile = null;
        });

        // Categories management
        const categories = <?= json_encode($oldSelectedCategories) ?>;
        const categoryListContainer = document.getElementById('categoryList');
        const addCategoryBtn = document.getElementById('addCategoryBtn');
        const addCategorySelect = document.getElementById('addCategorySelect');

        function createCategoryBadge(id, content) {
            const categoryBadge = document.createElement('span');
            categoryBadge.className = 'category-badge d-inline-flex align-items-center ms-2';
            categoryBadge.innerHTML = `
                <span>${content}</span>
                <input type="hidden" name="categories[]" value="${id}">
                <button type="button" class="category-badge-close" data-remove-id="${id}">
                    <i class="bi bi-x"></i>
                </button>
            `;
            return categoryBadge;
        }

        function removeBadgeById(id) {
            categories.forEach((category, index) => {
                if (parseInt(category.id) === parseInt(id)) {
                    categories.splice(index, 1);
                }
            });
        }

        function renderCategoryBadges() {
            categoryListContainer.innerHTML = '';
            if (categories.length === 0) {
                categoryListContainer.innerHTML = '<div class="text-muted small fst-italic">No categories selected</div>';
                return;
            }
            
            categories.forEach(category => {
                categoryListContainer.appendChild(createCategoryBadge(category.id, category.content));
            });
        }

        addCategoryBtn.addEventListener('click', () => {
            if (categories.some(category => parseInt(category.id) === parseInt(addCategorySelect.value))) {
                return;
            }
            const content = addCategorySelect.options[addCategorySelect.selectedIndex].text;
            categories.push({id: addCategorySelect.value, content: content});
            renderCategoryBadges();
        });
        
        categoryListContainer.addEventListener('click', event => {
            const removedBadge = event.target.closest('.category-badge-close');
            if (removedBadge) {
                const removeId = removedBadge.getAttribute('data-remove-id');
                removeBadgeById(removeId);
                renderCategoryBadges();
            }
        });

        // Preview functionality
        document.getElementById('previewBtn').addEventListener('click', () => {
            const title = document.getElementById('title').value || 'Untitled Blog';
            const content = $('#blogContent').summernote('code');
            const visibility = document.querySelector('input[name="visibility"]:checked').value;
            
            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewContent').innerHTML = content;
            
            // Set thumbnail preview
            const previewThumbnail = document.getElementById('previewThumbnail');
            if (thumbnailFile) {
                // Use FileReader for newly selected file
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewThumbnail.src = e.target.result;
                    previewThumbnail.style.display = 'block';
                }
                reader.readAsDataURL(thumbnailFile);
            } else if (thumbnailPreview.style.display !== 'none') {
                // Use the existing thumbnail (from old value)
                previewThumbnail.src = thumbnailPreview.src;
                previewThumbnail.style.display = 'block';
            } else {
                previewThumbnail.style.display = 'none';
            }
            
            // Set visibility badge
            const visibilityElement = document.getElementById('previewVisibility');
            if (visibility === 'private') {
                visibilityElement.innerHTML = '<span class="badge bg-danger"><i class="bi bi-lock-fill"></i> Private</span>';
            } else {
                visibilityElement.innerHTML = '<span class="badge bg-success"><i class="bi bi-globe"></i> Public</span>';
            }
            
            // Set categories
            const categoriesElement = document.getElementById('previewCategories');
            categoriesElement.innerHTML = '';
            if (categories.length > 0) {
                categories.forEach(category => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-secondary me-1';
                    badge.textContent = category.content;
                    categoriesElement.appendChild(badge);
                });
            } else {
                categoriesElement.innerHTML = '<span class="text-muted">No categories</span>';
            }
            
            // Show modal
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            previewModal.show();
        });
        
        if (categories) {
            renderCategoryBadges();
        }
    });
</script>
<?= $this->endSection() ?>
