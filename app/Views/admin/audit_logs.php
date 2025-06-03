<?= $this->extend('templates/base') ?>

<?= $this->section('title') ?>
Admin Audit Log
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Admin Audit Log</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">System Activity Log</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($logs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>User</th>
                                                <th>Action</th>
                                                <th>Entity Type</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($logs as $log): ?>
                                                <tr>
                                                    <td><?= (new DateTime($log['created_at']))->format('F j, Y g:i A') ?></td>
                                                    <td>
                                                        <?= $log['first_name'] ?> <?= $log['last_name'] ?>
                                                        <br>
                                                        <small class="text-muted"><?= $log['email'] ?></small>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $actionIcon = match($log['action_type']) {
                                                            'blog_create' => 'bi-file-earmark-plus',
                                                            'blog_update' => 'bi-pencil',
                                                            'blog_delete' => 'bi-trash',
                                                            'blog_like' => 'bi-hand-thumbs-up',
                                                            'blog_dislike' => 'bi-hand-thumbs-down',
                                                            'comment_create' => 'bi-chat',
                                                            'comment_reply' => 'bi-reply',
                                                            'login' => 'bi-box-arrow-in-right',
                                                            'logout' => 'bi-box-arrow-right',
                                                            default => 'bi-activity'
                                                        };
                                                        ?>
                                                        <i class="bi <?= $actionIcon ?> me-2"></i>
                                                        <?= ucwords(str_replace('_', ' ', $log['action_type'])) ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            <?= ucfirst($log['entity_type']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= esc($log['details']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="mt-4">
                                    <?= $pager->links() ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">No activity logs found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<?= $this->endSection() ?> 