<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="mb-4">Email Verification Required</h2>
                    <p class="text-muted mb-4">Please verify your email address to access the dashboard.</p>
                    <a href="<?= base_url('resend-verification') ?>" class="btn btn-primary">Resend Verification Email</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 