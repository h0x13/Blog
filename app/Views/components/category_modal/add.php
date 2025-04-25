<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url('categories/add') ?>" enctype="multipart/form-data" id="addCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_category">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="addCategory" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>" id="addCategory" name="name" required>
                            <?php if (isset($validation) && $validation->hasError('name')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
