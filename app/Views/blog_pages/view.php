<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog App' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            line-height: 1.7;
            background-color: #f5f5f5;
            padding-top: 70px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
        }
        
        .article-header {
            margin-bottom: 3rem;
        }
        
        .thumbnail-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2.5rem;
        }
        
        .thumbnail-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .thumbnail-container:hover img {
            transform: scale(1.02);
        }
        
        .article-title {
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .author-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .author-avatar {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        
        .author-meta {
            text-align: center;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }
        
        .author-name {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .reading-time {
            font-size: 0.85rem;
            color: var(--dark-gray);
        }
        
        .category-badge {
            background-color: var(--light-gray);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem 0.5rem 0;
            border-radius: 50px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .category-badge:hover {
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
        }
        
        article {
            font-size: 1.1rem;
            color: #444;
        }
        
        article img {
            max-width: 100%;
            border-radius: 8px;
            margin: 2rem 0;
        }
        
        article p {
            margin-bottom: 1.5rem;
        }
        
        article h2 {
            margin: 2.5rem 0 1.5rem;
            color: var(--primary-color);
        }
        
        article h3 {
            margin: 2rem 0 1.25rem;
            color: var(--secondary-color);
        }
        
        .back-button {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.2s ease;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        .navbar {
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        footer {
            box-shadow: 0 -2px 15px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .author-avatar {
                width: 80px;
                height: 80px;
            }
            
            article {
                font-size: 1rem;
            }
        }

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">Blog App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/blogs') ?>">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/categories') ?>">Categories</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/login') ?>">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <div class="container-fluid py-2">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Blog Header -->
                    <header class="article-header">
                        <?php if (isset($blog['thumbnail'])) { ?>
                            <div class="thumbnail-container">
                                <img src="<?= base_url('blogs/thumbnail/' . $blog['thumbnail']) ?>" class="img-fluid" alt="<?= esc($blog['title']) ?>">
                            </div>
                        <?php } ?>
                        
                        <h1 class="article-title text-center"><?= esc($blog['title']) ?></h1>
                        
                        <!-- Author and Date -->
                        <div class="author-container">
                            <img src="<?= isset($author['image'])? base_url('blogs/thumbnail/' . $author['image']): base_url('assets/img/default-avatar.svg') ?>" class="author-avatar">
                            <div class="author-meta">
                                <div class="mb-1">
                                    Written by <span class="author-name"><?= esc($author['first_name'] . ' ' . $author['last_name']) ?></span>
                                </div>
                                <div class="mb-1">
                                    Last updated <?= (new DateTime($blog['updated_at']))->format('F j, Y') ?>
                                </div>
                                <div class="reading-time">
                                    <i class="bi bi-clock"></i> <?= estimate_reading_time($blog['content']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <?php if(!empty($categories)): ?>
                            <div class="d-flex flex-wrap justify-content-center mb-4">
                                <?php foreach($categories as $category): ?>
                                    <a href="#" class="category-badge">
                                        <?= esc($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <!-- Blog Content -->
                    <article class="mb-5">
                        <?= $blog['content'] ?>
                    </article>
                    
                    <!-- Back to Blogs Button -->
                    <div class="d-flex justify-content-center mb-5">
                        <a href="<?= previous_url() ?>" class="btn btn-outline-primary back-button">
                            <i class="bi bi-arrow-left me-2"></i> Back to All Articles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <h5 class="mb-3">Blog App</h5>
                    <p class="small">Sharing knowledge and ideas through thoughtful writing.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="mb-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    </div>
                    <p class="small mb-0">&copy; <?= date('Y') ?> Blog App. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
