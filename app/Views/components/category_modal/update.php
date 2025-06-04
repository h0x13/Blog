<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?= base_url('categories/update') ?>" enctype="multipart/form-data" id="editCategoryForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_category">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="editCategoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>" id="editCategoryName" name="name" required>
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
