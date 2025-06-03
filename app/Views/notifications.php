<?= $this->extend('templates/base') ?>

<?= $this->section('title') ?>
Notifications
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
                <div class="col-sm-6">
                    <h3 class="mb-0">Notifications</h3>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <?= $this->include('blog_pages/message') ?>

        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (isset($notifications) && !empty($notifications)): ?>
                                <div class="notification-list">
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notification-item p-3 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="notification-icon me-3">
                                                    <i class="bi bi-bell-fill text-primary"></i>
                                                </div>
                                                <div class="notification-content flex-grow-1">
                                                    <p class="mb-1"><?= esc($notification['message']) ?></p>
                                                    <small class="text-muted">
                                                        <?= (new DateTime($notification['created_at']))->format('F j, Y g:i A') ?>
                                                    </small>
                                                </div>
                                                <?php if (!$notification['is_read']): ?>
                                                    <div class="notification-status">
                                                        <span class="badge bg-primary">New</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-bell-slash display-1 text-muted"></i>
                                    <p class="mt-3 text-muted">No notifications yet</p>
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
<!--end::App Main-->

<style>
.notification-item {
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: var(--bs-tertiary-bg);
}

.notification-icon {
    font-size: 1.25rem;
}

.notification-content p {
    margin-bottom: 0.25rem;
}

.notification-status {
    margin-left: 1rem;
}
</style>
<?= $this->endSection() ?> 