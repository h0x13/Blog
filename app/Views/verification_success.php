<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified - BlogHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .form-container {
            background-color: #ffffff;
            min-width: 400px;
            max-width: 500px;
            width: 100%;
        }
        .dark-background {
            background-color: #1a1a1a;
        }
    </style>
</head>
<body>
    <section class="d-flex flex-column align-items-center justify-content-center dark-background">
        <h2 class="fw-bold fs-3 text-white mb-4">BlogHub</h2>
        <div class="form-container d-flex flex-column align-items-center justify-content-center rounded p-4">
            <div class="text-center mb-4">
                <i class="bi bi-check-circle-fill text-success fa-3x mb-3"></i>
                <h3 class="text-primary mb-4 fs-3 fw-bold">Email Verified Successfully!</h3>
                <p class="mb-4">
                    Your email has been verified. You can now proceed to login and start using your account.
                </p>
                <a href="<?= base_url('login') ?>" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Go to Login
                </a>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 