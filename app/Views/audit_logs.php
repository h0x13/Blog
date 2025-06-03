<?= $this->extend('templates/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activity Log</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($logs) && !empty($logs)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
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
                                            <td><?= esc($log['details']) ?></td>
                                            <td><?= (new DateTime($log['created_at']))->format('F j, Y g:i A') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
<?= $this->endSection() ?> 
