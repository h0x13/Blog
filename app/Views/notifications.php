<?= $this->extend('templates/regular_user/base.php') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notifications</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" id="markAllRead">
                            <i class="bi bi-check-all"></i> Mark all as read
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($notifications)): ?>
                            <div class="list-group-item text-center py-4">
                                <i class="bi bi-bell-slash fs-1 text-muted"></i>
                                <p class="mt-2 mb-0">No notifications yet</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="list-group-item notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" 
                                     data-id="<?= $notification['notification_id'] ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($notification['message']) ?></h6>
                                        <small class="text-muted">
                                            <?= date('M d, Y H:i', strtotime($notification['created_at'])) ?>
                                        </small>
                                    </div>
                                    <?php if (isset($notification['blog_title'])): ?>
                                        <a href="/blogs/view/<?= $notification['blog_slug'] ?>" class="text-decoration-none">
                                            <small class="text-muted">View: <?= esc($notification['blog_title']) ?></small>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark single notification as read
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.dataset.id;
            if (!this.classList.contains('unread')) return;

            fetch(`/notifications/mark-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.remove('unread');
                    updateUnreadCount();
                }
            });
        });
    });

    // Mark all as read
    document.getElementById('markAllRead').addEventListener('click', function() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                updateUnreadCount();
            }
        });
    });

    function updateUnreadCount() {
        fetch('/notifications/unread-count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.nav-link[href="/notifications"] .badge');
            if (data.count > 0) {
                if (!badge) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge bg-danger';
                    newBadge.textContent = data.count;
                    document.querySelector('.nav-link[href="/notifications"]').appendChild(newBadge);
                } else {
                    badge.textContent = data.count;
                }
            } else if (badge) {
                badge.remove();
            }
        });
    }
});
</script>

<style>
.notification-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: var(--bs-light);
}

.notification-item.unread {
    background-color: var(--bs-light);
    border-left: 4px solid var(--bs-primary);
}

.notification-item.unread h6 {
    font-weight: 600;
}
</style>
<?= $this->endSection() ?> 
