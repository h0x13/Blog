<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogHub - Your Personal Blogging Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .navbar { background: #000910; }
        .nav-link { 
            position: relative; 
            padding-bottom: 5px; 
            text-transform: uppercase; 
        }
        .nav-link::after { 
            content: ''; 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            width: 0; 
            height: 2px; 
            background: #0d6efd; 
            transition: width 0.3s ease; 
        }
        .nav-link:hover::after { width: 100%; }
        .hero-section { background: #000910; min-height: 800px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="#">
                BlogHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white mx-3" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white mx-3" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link text-white mx-3" href="#">Discover</a></li>
                    <li class="nav-item"><a class="nav-link text-white mx-3" href="#">Categories</a></li>
                    <li class="nav-item"><a class="nav-link text-white mx-3" href="#">Contact</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light rounded-3 ms-3" href="#">SIGN IN</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section d-flex align-items-center justify-content-center text-center">
        <div class="container">
            <h1 class="text-white display-4 fw-bold mb-4">EXPRESS. SHARE. CONNECT.</h1>
            <p class="text-secondary mb-5 fs-5">Your personal platform to create beautiful blogs and connect with readers</p>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="btn btn-primary rounded-3 px-4 py-2">CREATE YOUR BLOG</a>
                <a href="#" class="btn btn-outline-light rounded-3 px-4 py-2">Explore & Read Blogs</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 