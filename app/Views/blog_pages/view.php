<?= $this->extend('templates/base') ?>

<?= $this->section('title') ?>
<?= esc($blog['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!--begin::App Main-->
<main class="app-main position-relative">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!-- Back Button -->
                <div class="col-12">
                    <a href="<?= base_url('/blogs') ?>" class="btn btn-secondary position-absolute">
                        <i class="bi bi-arrow-left"></i>
                    </a>
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
                                    <a href="<?= base_url("blogs/category/{$category['name']}") ?>" class="category-badge">
                                        <?= esc($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <!-- Blog Content -->
                    <div class="blog-content">
                        <?= $blog['content'] ?>
                    </div>

                    <!-- Include Comments Section -->
                    <?= view('blog_pages/_comments') ?>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main-->

<!-- Back to Top Button -->
<button id="back-to-top" class="btn btn-primary back-to-top" title="Back to Top">
    <i class="bi bi-arrow-up"></i>
</button>
    
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
        
    [data-bs-theme="dark"] {
        --primary-color: #e9ecef;
        --secondary-color: #dee2e6;
        --accent-color: #3498db;
        --text-color: #e9ecef;
        --light-gray: #212529;
        --medium-gray: #343a40;
        --dark-gray: #adb5bd;
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
        color: var(--text-color);
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
        border: 3px solid var(--light-gray);
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
        
    .blog-content {
            font-size: 1.1rem;
        color: var(--text-color);
        }
        
    .blog-content img {
            max-width: 100%;
            border-radius: 8px;
            margin: 2rem 0;
        }
        
    .blog-content p {
            margin-bottom: 1.5rem;
        }
        
    .blog-content h2 {
            margin: 2.5rem 0 1.5rem;
            color: var(--primary-color);
        }
        
    .blog-content h3 {
            margin: 2rem 0 1.25rem;
            color: var(--secondary-color);
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        background-color: var(--primary-color);
        color: var(--light-gray);
    }

    /* Back to Top Button Styles */
    .back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        padding: 0;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        transform: translateY(-5px);
        }
        
        @media (max-width: 768px) {
            .article-title {
                font-size: 2rem;
            }
            
            .author-avatar {
                width: 80px;
                height: 80px;
            }
            
        .blog-content {
                font-size: 1rem;
            }
        }
    </style>

<script>
    // Back to Top Button Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
<?= $this->endSection() ?>
