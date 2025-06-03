DROP DATABASE IF EXISTS blogdb;
CREATE DATABASE blogdb;
USE blogdb;

CREATE TABLE users (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    image VARCHAR(255) DEFAULT NULL,
    birthdate DATE,
    gender VARCHAR(50),
    theme ENUM('dark', 'light'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE blogs (
    blog_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    thumbnail VARCHAR(255),
    visibility ENUM('private', 'public') NOT NULL DEFAULT 'private',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COLLATE utf8_general_ci UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE blog_categories (
    blog_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (blog_id, category_id),
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE email_verifications (
    verification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
    reset_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE comment_replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE blog_reactions (
    reaction_id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    reaction_type ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_blog_user_reaction (blog_id, user_id)
);

CREATE TABLE comment_reactions (
    reaction_id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    reaction_type ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_comment_user_reaction (comment_id, user_id)
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    reference_id INT,
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO users (first_name, last_name, middle_name, email, password, role)
VALUES 
('admin', 'admin', NULL, 'admin@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'admin'),
('Alex', 'Johnson', NULL, 'alex.johnson@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Jordan', 'Lee', NULL, 'jordan.lee@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Taylor', 'Smith', 'Morgan', 'taylor.smith@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Morgan', 'Davis', NULL, 'morgan.davis@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Riley', 'Thompson', NULL, 'riley.thompson@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user'),
('Skyler', 'Reed', NULL, 'skyler.reed@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'user');

INSERT INTO categories (name)
VALUES
('technology'),
('productivity'),
('design'),
('wellness'),
('travel'),
('lifestyle'),
('culture'),
('education'),
('career'),
('marketing'),
('photography'),
('writing'),
('entrepreneurship'),
('personal finance'),
('startups'),
('remote work'),
('freelancing'),
('artificial intelligence'),
('cybersecurity'),
('frontend development'),
('backend development'),
('mobile apps'),
('user experience'),
('cloud computing'),
('e-commerce'),
('data science'),
('machine learning'),
('devops'),
('blockchain'),
('open source');


INSERT INTO blogs (user_id, title, slug, content, thumbnail, visibility) VALUES
(2, 'YouTube Tutorial: Database Design with MySQL Workbench', 'youtube-tutorial-database-design-with-mysql-workbench-1', '<p>This video guides you through creating an Entity-Relationship Diagram (ERD) for a blog platform, covering essential components like users, posts, and categories.</p><p>For a visual walkthrough of designing a blog database schema using MySQL Workbench, consider watching this tutorial:</p><p><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/u382hjH_cGo\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe></p><h4 class=\"\">📘 <b>Comprehensive Guide: Designing a Blog Database Schema</b></h4><p data-start=\"399\" data-end=\"477\" class=\"\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">For a detailed, step-by-step guide on structuring a blog database, the DragonflyDB article provides in-depth explanations and examples:</span></p><ul><li><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">This resource delves into key entities such as users, posts, comments, and categories, offering best practices for schema design and optimization.</span></li></ul><h4 data-start=\"600\" data-end=\"656\" class=\"\">🛠️ GitHub Repository: Blog Database Schema in MySQL</h4><p data-start=\"658\" data-end=\"736\" class=\"\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">If you\'re looking for a practical example with SQL scripts, this GitHub repository offers a complete blog database schema:</span></p><ul><li><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">It includes SQL files and visual representations to help you implement and understand the database structure effectively.</span></li><li><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">These resources should provide you with a comprehensive understanding of blog database schemas, enhancing both your theoretical knowledge and practical skills.</span></li></ul><h4 class=\"\"><br></h4>', '1746687003_25f2db7243e7d818080d.webp', 'public'),
(2, 'Mastering Morning Routines: 3 Habits to Transform Your Day', 'mastering-morning-routines-3-habits-to-transform-your-day-1', '<h1 class=\"\"><b>Introduction</b></h1><p class=\"\">Have you ever felt like your mornings are chaotic and set a negative tone for the rest of the day? You\'re not alone. The way we start our mornings can deeply affect our productivity, mood, and mindset. In this post, we\'ll explore three powerful habits that can help you master your morning routine and set yourself up for success.</p><h4 data-start=\"601\" data-end=\"654\" class=\"\"><strong data-start=\"606\" data-end=\"652\">1. Wake Up with Intention (Not Your Phone)</strong></h4><p data-start=\"655\" data-end=\"829\" class=\"\">Most of us are guilty of checking our phones first thing in the morning. Instead, try starting your day with 5 minutes of deep breathing or journaling to set your intentions.</p><p data-start=\"831\" data-end=\"997\" class=\"\"><strong data-start=\"831\" data-end=\"893\">Watch this video for a guided 5-minute morning meditation:</strong></p><p data-start=\"831\" data-end=\"997\" class=\"\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/inpok4MKVLM\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><strong data-start=\"831\" data-end=\"893\"><br></strong></p><h4 data-start=\"1004\" data-end=\"1044\" class=\"\"><strong data-start=\"1009\" data-end=\"1042\">2. Hydrate and Move Your Body</strong></h4><p data-start=\"1045\" data-end=\"1199\" class=\"\">Your body becomes dehydrated overnight. Drinking a glass of water and doing light stretching or a short workout gets your blood flowing and boosts energy.</p><p data-start=\"1201\" data-end=\"1334\" class=\"\"><strong data-start=\"1201\" data-end=\"1245\">Try this 10-minute morning yoga session:</strong></p><p data-start=\"1201\" data-end=\"1334\" class=\"\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/4pKly2JojMw\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><strong data-start=\"1201\" data-end=\"1245\"><br></strong></p><h4 data-start=\"1341\" data-end=\"1376\" class=\"\"><strong data-start=\"1346\" data-end=\"1374\">3. Set Top 3 Daily Goals</strong></h4><p data-start=\"1377\" data-end=\"1521\" class=\"\">Rather than diving into a to-do list, focus on identifying your top three priorities for the day. This keeps you focused and prevents overwhelm.</p><p data-start=\"1523\" data-end=\"1689\" class=\"\"><strong data-start=\"1523\" data-end=\"1590\">Watch this productivity coach explain the \"Top 3 Goals\" method:</strong></p><p data-start=\"1523\" data-end=\"1689\" class=\"\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/c-ZdxJtxfAo\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><strong data-start=\"1523\" data-end=\"1590\"><br></strong></p><h3 data-start=\"1696\" data-end=\"1720\" class=\"\"><strong data-start=\"1700\" data-end=\"1718\">Final Thoughts</strong></h3><p data-start=\"1721\" data-end=\"1915\" class=\"\">Your morning routine doesn\'t need to be long or complicated. Start with just one habit, be consistent, and gradually build up. A well-structured morning can truly transform the rest of your day.</p><p data-start=\"1523\" data-end=\"1689\" class=\"\"><br></p>', '1746769254_976c914292acd73c2fa8.jpg', 'private');



INSERT INTO blog_categories (blog_id, category_id)
VALUES
(1, 1),
(1, 21),
(1, 28),
(1, 30),
(2, 4),
(2, 6);






-- Blogs TESTING
INSERT INTO blogs (user_id, title, slug, content, thumbnail, visibility) VALUES
(3, 'The Future of AI in Everyday Life', 'the-future-of-ai-in-everyday-life', 'Content about AI', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(4, '10 Productivity Hacks You Need', '10-productivity-hacks-you-need', 'Content about productivity', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(5, 'Top 5 Places to Travel in 2025', 'top-5-places-to-travel-in-2025', 'Travel blog content', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(6, 'Mastering Freelancing in the Digital Age', 'mastering-freelancing-in-the-digital-age', 'Content about freelancing', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(7, 'Design Thinking for Developers', 'design-thinking-for-developers', 'Content about design and dev', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(2, 'Understanding Blockchain Beyond Crypto', 'understanding-blockchain-beyond-crypto', 'Blockchain blog', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(3, 'E-commerce Strategies that Work', 'ecommerce-strategies-that-work', 'E-commerce blog', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(4, 'Photography Tips for Beginners', 'photography-tips-for-beginners', 'Content on photography', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(5, 'Balancing Career and Personal Life', 'balancing-career-and-personal-life', 'Career & life balance', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(6, 'How to Build Mobile Apps with Flutter', 'how-to-build-mobile-apps-with-flutter', 'Flutter content', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(7, 'Cybersecurity in Remote Work', 'cybersecurity-in-remote-work', 'Remote security', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(2, 'Marketing Funnels Explained', 'marketing-funnels-explained', 'Marketing basics', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(3, 'Best Tools for Remote Teams', 'best-tools-for-remote-teams', 'Remote tools', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(4, 'How to Write Engaging Blog Posts', 'how-to-write-engaging-blog-posts', 'Writing content', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(5, 'The Rise of DevOps in 2025', 'the-rise-of-devops-in-2025', 'DevOps content', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(6, 'Machine Learning vs. Data Science', 'machine-learning-vs-data-science', 'Comparison ML & DS', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(7, 'Effective UX Principles for Apps', 'effective-ux-principles-for-apps', 'UX blog', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(2, 'Career Growth in Tech', 'career-growth-in-tech', 'Career advice', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(3, 'Open Source Contributions: Getting Started', 'open-source-contributions-getting-started', 'Open source tips', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(4, 'Top Startup Mistakes to Avoid', 'top-startup-mistakes-to-avoid', 'Startup blog', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(5, 'Creating a Lifestyle That Supports Creativity', 'creating-a-lifestyle-that-supports-creativity', 'Lifestyle blog', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(6, 'Developing Backend APIs with Node.js', 'developing-backend-apis-with-nodejs', 'Node.js content', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(7, 'Master Personal Finance with These 5 Rules', 'master-personal-finance-with-these-5-rules', 'Finance tips', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(2, 'Education Tech Trends for the Next Decade', 'education-tech-trends-for-the-next-decade', 'EdTech blog', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(3, 'Health and Wellness at Work', 'health-and-wellness-at-work', 'Wellness content', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(4, 'Culture and Communication in Multinational Teams', 'culture-and-communication-in-multinational-teams', 'Culture & comms', '1746769254_976c914292acd73c2fa8.jpg', 'public'),
(5, 'Design Systems That Scale', 'design-systems-that-scale', 'Design systems blog', '1746769254_976c914292acd73c2fa8.jpg', 'private'),
(6, 'Frontend Development in React', 'frontend-development-in-react', 'React content', '1746769254_976c914292acd73c2fa8.jpg', 'public');

-- Blog Categories
INSERT INTO blog_categories (blog_id, category_id) VALUES
(3, 18), (3, 1),
(4, 2),
(5, 5),
(6, 17),
(7, 3), (7, 20),
(8, 29),
(9, 25), (9, 6),
(10, 22), (10, 21),
(11, 19), (11, 16),
(12, 10),
(13, 16),
(14, 12),
(15, 27),
(16, 26), (16, 27),
(17, 23), (17, 22),
(18, 9),
(19, 30),
(20, 15),
(21, 6),
(22, 20),
(23, 14),
(24, 8),
(25, 4),
(26, 7),
(27, 3),
(28, 21);
