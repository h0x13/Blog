<?= $this->extend('templates/home') ?>

<?= $this->section('content') ?>
<main class="main">
    <section id="userForm" class="user-form section d-flex flex-column align-items-center justify-content-center dark-background">
        <h2 class="fw-bold fs-3">BlogHub</h2>
        <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
            <h3 class="text-primary mb-4 fs-3 fw-bold">Email Verification</h3>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?php if (is_array(session('error'))): ?>
                        <?php foreach (session('error') as $error): ?>
                            <p class="mb-0"><?= esc($error) ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="mb-0"><?= esc(session('error')) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <p class="mb-0"><?= esc($success) ?></p>
                </div>
            <?php endif; ?>

            <div class="text-center mb-4">
                <div id="verificationStatus">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">
                        Waiting for email verification...<br>
                        <small class="text-muted">Please check your email and click the verification link.</small>
                    </p>
                </div>
            </div>

            <div class="alert alert-info mb-4">
                <p class="mb-0">
                    A verification email has been sent to:<br>
                    <strong class="text-primary"><?= esc(session('temp_email')) ?></strong><br>
                    <small class="text-muted">The verification link will expire in 5 minutes.</small>
                </p>
            </div>

            <p class="text-center text-muted mb-0">
                <small>If you don't see the email, please check your spam folder.</small>
            </p>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let isChecking = true;
    let checkCount = 0;
    const maxChecks = 60; // 5 minutes (5 seconds * 60)

    // Check verification status every 5 seconds
    const checkVerification = setInterval(function() {
        if (!isChecking) {
            clearInterval(checkVerification);
            return;
        }

        checkCount++;
        if (checkCount >= maxChecks) {
            clearInterval(checkVerification);
            document.getElementById('verificationStatus').innerHTML = `
                <i class="bi bi-exclamation-circle-fill text-danger fa-3x mb-3"></i>
                <p class="mb-0">
                    Verification link expired.<br>
                    <small class="text-muted">Please contact support for assistance.</small>
                </p>
            `;
            return;
        }

        fetch('<?= base_url('verification/check-status') ?>')
            .then(response => response.json())
            .then(data => {
                if (+data.verified) {
                    isChecking = false;
                    clearInterval(checkVerification);
                    document.getElementById('verificationStatus').innerHTML = `
                        <i class="bi bi-check-circle-fill text-success fa-3x mb-3"></i>
                        <p class="mb-3">
                            Email verified successfully!<br>
                            <small class="text-muted">Your email <?= esc(session('temp_email')) ?> has been verified.</small>
                        </p>
                        <a href="<?= base_url('login') ?>" class="btn btn-primary" onclick="stopChecking()">Go to Login</a>
                    `;
                } else {
                    // Keep showing loading spinner if not verified
                    document.getElementById('verificationStatus').innerHTML = `
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mb-0">
                            Waiting for email verification...<br>
                            <small class="text-muted">Please check your email and click the verification link.</small>
                        </p>
                    `;
                }
            })
            .catch(error => {
                console.error('Error checking verification status:', error);
                isChecking = false;
                clearInterval(checkVerification);
                document.getElementById('verificationStatus').innerHTML = `
                    <i class="bi bi-exclamation-circle-fill text-danger fa-3x mb-3"></i>
                    <p class="mb-0">
                        Error checking verification status.<br>
                        <small class="text-muted">Please refresh the page and try again.</small>
                    </p>
                `;
            });
    }, 5000);

    // Function to stop checking verification status
    window.stopChecking = function() {
        isChecking = false;
        clearInterval(checkVerification);
    };
});
</script>
<?= $this->endSection() ?> 