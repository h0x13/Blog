<?php
$baseTemplate = session()->get('role') === 'admin' ? 'templates/base' : 'templates/regular_user/base';
?>

<?= $this->extend($baseTemplate) ?>

<?= $this->section('title') ?>
Audit Logs
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
                    <h1 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Audit Logs
                    </h1>
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
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Activity History</h5>
                                <div class="d-flex gap-2">
                                    <!-- Filter Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-funnel me-1"></i>Filter
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                            <li><a class="dropdown-item" href="<?= base_url('audit-logs?action=all') ?>">All Actions</a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('audit-logs?action=create') ?>">Create</a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('audit-logs?action=update') ?>">Update</a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('audit-logs?action=delete') ?>">Delete</a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('audit-logs?action=login') ?>">Login</a></li>
                                        </ul>
                                    </div>
                                    <!-- Export Button -->
                                    <a href="<?= base_url('audit-logs/export') ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-download me-1"></i>Export
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>Action</th>
                                            <th>Entity Type</th>
                                            <th>Details</th>
                                            <th>IP Address</th>
                                            <?php if (session()->get('role') === 'admin'): ?>
                                            <th>User</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($logs)): ?>
                                        <tr>
                                            <td colspan="<?= session()->get('role') === 'admin' ? '6' : '5' ?>" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    No audit logs found
                                                </div>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($logs as $log): ?>
                                            <tr>
                                                <td><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= getActionBadgeColor($log['action']) ?>">
                                                        <?= ucfirst(esc($log['action'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= ucfirst(esc($log['entity_type'])) ?></td>
                                                <td><?= esc($log['details']) ?></td>
                                                <td><?= esc($log['ip_address']) ?></td>
                                                <?php if (session()->get('role') === 'admin'): ?>
                                                <td>
                                                    <a href="<?= base_url('admin/users/view/' . $log['user_id']) ?>" class="text-decoration-none">
                                                        <?= esc($log['user_name']) ?>
                                                    </a>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if (isset($pager)): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing <?= $pager->getCurrentPage() * $pager->getPerPage() - $pager->getPerPage() + 1 ?> 
                                    to <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
                                    of <?= $pager->getTotal() ?> entries
                                </div>
                                <?= $pager->links() ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->

<?php
function getActionBadgeColor($action) {
    return match(strtolower($action)) {
        'create' => 'success',
        'update' => 'primary',
        'delete' => 'danger',
        'login' => 'info',
        default => 'secondary'
    };
}
?>

<?= $this->endSection() ?> 