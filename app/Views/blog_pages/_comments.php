<!-- Blog Reactions -->
<div class="blog-reactions mb-4">
    <button class="btn btn-outline-primary me-2 blog-reaction-btn" data-reaction="like" data-blog-id="<?= $blog['blog_id'] ?>">
        <i class="bi bi-hand-thumbs-up<?= isset($user_blog_reaction) && $user_blog_reaction['reaction_type'] === 'like' ? '-fill' : '' ?>"></i>
        <span class="like-count"><?= $blog_reactions['likes'] ?? 0 ?></span>
    </button>
    <button class="btn btn-outline-primary blog-reaction-btn" data-reaction="dislike" data-blog-id="<?= $blog['blog_id'] ?>">
        <i class="bi bi-hand-thumbs-down<?= isset($user_blog_reaction) && $user_blog_reaction['reaction_type'] === 'dislike' ? '-fill' : '' ?>"></i>
        <span class="dislike-count"><?= $blog_reactions['dislikes'] ?? 0 ?></span>
    </button>
</div>

<!-- Comments Section -->
<div class="comments-section">
    <h4 class="mb-4">Comments</h4>
    
    <!-- Add Comment Form -->
    <div class="add-comment mb-4">
        <form id="commentForm" class="mb-3">
            <input type="hidden" name="blog_id" value="<?= $blog['blog_id'] ?>">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="form-group">
                <textarea class="form-control" name="content" rows="3" placeholder="Add a comment..." required></textarea>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-primary">Comment</button>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div id="commentsList">
        <?php foreach ($comments as $comment): ?>
            <div class="comment mb-4" data-comment-id="<?= $comment['comment_id'] ?>">
                <div class="d-flex">
                    <img src="<?= $comment['image'] ? base_url('user-image/' . $comment['image']) : base_url('assets/img/default-avatar.svg') ?>" 
                         class="rounded-circle me-3" 
                         alt="User Avatar"
                         style="width: 40px; height: 40px;">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            <h6 class="mb-0 me-2">
                                <?= $comment['first_name'] ?> <?= $comment['middle_name'] ?? '' ?> <?= $comment['last_name'] ?>
                            </h6>
                            <?php if ($comment['user_id'] === $blog['user_id']): ?>
                                <span class="badge bg-primary">Author</span>
                            <?php endif; ?>
                            <small class="text-muted ms-2">
                                <?= date('M d, Y', strtotime($comment['created_at'])) ?>
                            </small>
                        </div>
                        <p class="mb-2"><?= $comment['content'] ?></p>
                        
                        <!-- Comment Reactions -->
                        <div class="comment-reactions mb-2">
                            <button class="btn btn-sm btn-link comment-reaction-btn" 
                                    data-reaction="like" 
                                    data-comment-id="<?= $comment['comment_id'] ?>">
                                <i class="bi bi-hand-thumbs-up<?= isset($comment['user_reaction']) && $comment['user_reaction']['reaction_type'] === 'like' ? '-fill' : '' ?>"></i>
                                <span class="like-count"><?= $comment['reactions']['likes'] ?? 0 ?></span>
                            </button>
                            <button class="btn btn-sm btn-link comment-reaction-btn" 
                                    data-reaction="dislike" 
                                    data-comment-id="<?= $comment['comment_id'] ?>">
                                <i class="bi bi-hand-thumbs-down<?= isset($comment['user_reaction']) && $comment['user_reaction']['reaction_type'] === 'dislike' ? '-fill' : '' ?>"></i>
                                <span class="dislike-count"><?= $comment['reactions']['dislikes'] ?? 0 ?></span>
                            </button>
                            <button class="btn btn-sm btn-link reply-btn" data-comment-id="<?= $comment['comment_id'] ?>">
                                Reply
                            </button>
                        </div>

                        <!-- Reply Form (Hidden by default) -->
                        <div class="reply-form" style="display: none;">
                            <form class="replyForm mb-2">
                                <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                <div class="form-group">
                                    <textarea class="form-control form-control-sm" name="content" rows="2" placeholder="Add a reply..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="button" class="btn btn-sm btn-light me-2 cancel-reply">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                                </div>
                            </form>
                        </div>

                        <!-- Replies List -->
                        <div class="replies-list ms-4 mt-2">
                            <?php foreach ($comment['replies'] as $reply): ?>
                                <div class="reply mb-3">
                                    <div class="d-flex">
                                        <img src="<?= $reply['image'] ? base_url('user-image/' . $reply['image']) : base_url('assets/img/default-avatar.svg') ?>" 
                                             class="rounded-circle me-3" 
                                             alt="User Avatar"
                                             style="width: 32px; height: 32px;">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 me-2">
                                                    <?= $reply['first_name'] ?> <?= $reply['middle_name'] ?? '' ?> <?= $reply['last_name'] ?>
                                                </h6>
                                                <?php if ($reply['user_id'] === $blog['user_id']): ?>
                                                    <span class="badge bg-primary">Author</span>
                                                <?php endif; ?>
                                                <small class="text-muted ms-2">
                                                    <?= date('M d, Y', strtotime($reply['created_at'])) ?>
                                                </small>
                                            </div>
                                            <p class="mb-1"><?= $reply['content'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update CSRF token
    function updateCsrfToken(newToken) {
        // Update all hidden inputs with the new token
        document.querySelectorAll('input[name="<?= csrf_token() ?>"]').forEach(input => {
            input.value = newToken;
        });
        
        // Update all forms with the new token
        document.querySelectorAll('form').forEach(form => {
            const tokenInput = form.querySelector(`input[name="<?= csrf_token() ?>"]`);
            if (tokenInput) {
                tokenInput.value = newToken;
            }
        });
    }

    // Function to get current CSRF token
    function getCsrfToken() {
        const tokenInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
        if (!tokenInput) {
            console.error('CSRF token input not found');
            return '';
        }
        return tokenInput.value;
    }

    // Function to make AJAX request with CSRF handling
    async function makeRequest(url, formData) {
        try {
            // Always get the latest token before making the request
            const currentToken = getCsrfToken();
            if (!currentToken) {
                throw new Error('CSRF token not found');
            }
            formData.set('<?= csrf_token() ?>', currentToken);
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': currentToken
                },
                body: formData
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            // Update CSRF token from response
            if (data.csrf_token) {
                updateCsrfToken(data.csrf_token);
            }
            
            return data;
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }

    // Handle blog reactions
    document.querySelectorAll('.blog-reaction-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const blogId = this.dataset.blogId;
            const reactionType = this.dataset.reaction;
            
            // Disable button during request
            this.disabled = true;
            
            const formData = new FormData();
            formData.append('blog_id', blogId);
            formData.append('reaction_type', reactionType);
            
            try {
                const data = await makeRequest('<?= base_url('blogs/reaction/blog') ?>', formData);
                
                if (data.success) {
                    // Update like/dislike counts
                    document.querySelectorAll('.blog-reaction-btn').forEach(btn => {
                        const type = btn.dataset.reaction;
                        const countSpan = btn.querySelector(`.${type}-count`);
                        countSpan.textContent = data.reactions[type + 's'];
                        
                        // Update icon
                        const icon = btn.querySelector('i');
                        if (data.user_reaction && data.user_reaction.reaction_type === type) {
                            icon.classList.add('bi-hand-thumbs-' + type + '-fill');
                            icon.classList.remove('bi-hand-thumbs-' + type);
                        } else {
                            icon.classList.remove('bi-hand-thumbs-' + type + '-fill');
                            icon.classList.add('bi-hand-thumbs-' + type);
                        }
                    });
                } else if (data.error) {
                    alert(data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            } finally {
                this.disabled = false;
            }
        });
    });

    // Handle comment reactions
    document.querySelectorAll('.comment-reaction-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const commentId = this.dataset.commentId;
            const reactionType = this.dataset.reaction;
            
            // Disable button during request
            this.disabled = true;
            
            const formData = new FormData();
            formData.append('comment_id', commentId);
            formData.append('reaction_type', reactionType);
            
            try {
                const data = await makeRequest('<?= base_url('blogs/reaction/comment') ?>', formData);
                
                if (data.success) {
                    const comment = this.closest('.comment');
                    comment.querySelectorAll('.comment-reaction-btn').forEach(btn => {
                        const type = btn.dataset.reaction;
                        const countSpan = btn.querySelector(`.${type}-count`);
                        countSpan.textContent = data.reactions[type + 's'];
                        
                        // Update icon
                        const icon = btn.querySelector('i');
                        if (data.user_reaction && data.user_reaction.reaction_type === type) {
                            icon.classList.add('bi-hand-thumbs-' + type + '-fill');
                            icon.classList.remove('bi-hand-thumbs-' + type);
                        } else {
                            icon.classList.remove('bi-hand-thumbs-' + type + '-fill');
                            icon.classList.add('bi-hand-thumbs-' + type);
                        }
                    });
                } else if (data.error) {
                    alert(data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            } finally {
                this.disabled = false;
            }
        });
    });

    // Handle comment form submission
    document.getElementById('commentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        
        const formData = new FormData(this);
        
        try {
            const data = await makeRequest('<?= base_url('blogs/comment/add') ?>', formData);
            
            if (data.success) {
                // Clear form
                this.reset();
                
                const comment = data.comment;
                const commentHtml = `
                    <div class="comment mb-4" data-comment-id="${comment.comment_id}">
                        <div class="d-flex">
                            <img src="${comment.image ? '<?= base_url('user-image/') ?>' + comment.image : '<?= base_url('assets/img/default-avatar.svg') ?>'}" 
                                 class="rounded-circle me-3" 
                                 alt="User Avatar"
                                 style="width: 40px; height: 40px;">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 me-2">
                                        ${comment.first_name} ${comment.middle_name} ${comment.last_name}
                                    </h6>
                                    ${comment.user_id === <?= $blog['user_id'] ?> ? '<span class="badge bg-primary">Author</span>' : ''}
                                </div>
                                <p class="mb-2">${comment.content}</p>
                                
                                <div class="comment-reactions mb-2">
                                    <button class="btn btn-sm btn-link comment-reaction-btn" 
                                            data-reaction="like" 
                                            data-comment-id="${comment.comment_id}">
                                        <i class="bi bi-hand-thumbs-up"></i>
                                        <span class="like-count">0</span>
                                    </button>
                                    <button class="btn btn-sm btn-link comment-reaction-btn" 
                                            data-reaction="dislike" 
                                            data-comment-id="${comment.comment_id}">
                                        <i class="bi bi-hand-thumbs-down"></i>
                                        <span class="dislike-count">0</span>
                                    </button>
                                    <button class="btn btn-sm btn-link reply-btn" data-comment-id="${comment.comment_id}">
                                        Reply
                                    </button>
                                </div>

                                <div class="reply-form" style="display: none;">
                                    <form class="replyForm mb-2">
                                        <input type="hidden" name="comment_id" value="${comment.comment_id}">
                                        <input type="hidden" name="<?= csrf_token() ?>" value="${getCsrfToken()}">
                                        <div class="form-group">
                                            <textarea class="form-control form-control-sm" name="content" rows="2" placeholder="Add a reply..." required></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button type="button" class="btn btn-sm btn-light me-2 cancel-reply">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="replies-list ms-4 mt-2"></div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('commentsList').insertAdjacentHTML('afterbegin', commentHtml);
                
                // Add event listeners to new comment
                const newComment = document.querySelector(`[data-comment-id="${comment.comment_id}"]`);
                addCommentEventListeners(newComment);
            } else if (data.error) {
                alert(data.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request');
        } finally {
            submitBtn.disabled = false;
        }
    });

    // Handle reply buttons
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = this.closest('.comment').querySelector('.reply-form');
            replyForm.style.display = 'block';
        });
    });

    // Handle cancel reply buttons
    document.querySelectorAll('.cancel-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            const replyForm = this.closest('.reply-form');
            replyForm.style.display = 'none';
        });
    });

    // Handle reply form submissions
    document.querySelectorAll('.replyForm').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            try {
                const data = await makeRequest('<?= base_url('blogs/comment/reply') ?>', formData);
                
                if (data.success) {
                    // Clear form and hide it
                    this.reset();
                    this.closest('.reply-form').style.display = 'none';
                    
                    // Add new reply to the list
                    const repliesList = this.closest('.comment').querySelector('.replies-list');
                    data.reply.forEach(reply => {
                        const replyHtml = `
                            <div class="reply mb-3">
                                <div class="d-flex">
                                    <img src="${reply.image ? '<?= base_url('user-image/') ?>' + reply.image : '<?= base_url('assets/img/default-avatar.svg') ?>'}" 
                                         class="rounded-circle me-3" 
                                         alt="User Avatar"
                                         style="width: 32px; height: 32px;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <h6 class="mb-0 me-2">
                                                ${reply.first_name} ${reply.middle_name} ${reply.last_name}
                                            </h6>
                                            ${reply.user_id === <?= $blog['user_id'] ?> ? '<span class="badge bg-primary">Author</span>' : ''}
                                        </div>
                                        <p class="mb-1">${reply.content}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        repliesList.insertAdjacentHTML('beforeend', replyHtml);
                    });
                } else if (data.error) {
                    alert(data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            } finally {
                submitBtn.disabled = false;
            }
        });
    });

    // Function to add event listeners to a comment
    function addCommentEventListeners(comment) {
        // Add reaction listeners
        comment.querySelectorAll('.comment-reaction-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const commentId = this.dataset.commentId;
                const reactionType = this.dataset.reaction;
                
                // Disable button during request
                this.disabled = true;
                
                const formData = new FormData();
                formData.append('comment_id', commentId);
                formData.append('reaction_type', reactionType);
                
                try {
                    const data = await makeRequest('<?= base_url('blogs/reaction/comment') ?>', formData);
                    
                    if (data.success) {
                        const comment = this.closest('.comment');
                        comment.querySelectorAll('.comment-reaction-btn').forEach(btn => {
                            const type = btn.dataset.reaction;
                            const countSpan = btn.querySelector(`.${type}-count`);
                            countSpan.textContent = data.reactions[type + 's'];
                            
                            // Update icon
                            const icon = btn.querySelector('i');
                            if (data.user_reaction && data.user_reaction.reaction_type === type) {
                                icon.classList.add('bi-hand-thumbs-' + type + '-fill');
                                icon.classList.remove('bi-hand-thumbs-' + type);
                            } else {
                                icon.classList.remove('bi-hand-thumbs-' + type + '-fill');
                                icon.classList.add('bi-hand-thumbs-' + type);
                            }
                        });
                    } else if (data.error) {
                        alert(data.error);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request');
                } finally {
                    this.disabled = false;
                }
            });
        });

        // Add reply button listener
        const replyBtn = comment.querySelector('.reply-btn');
        replyBtn.addEventListener('click', function() {
            const replyForm = this.closest('.comment').querySelector('.reply-form');
            replyForm.style.display = 'block';
        });

        // Add cancel reply button listener
        const cancelBtn = comment.querySelector('.cancel-reply');
        cancelBtn.addEventListener('click', function() {
            const replyForm = this.closest('.reply-form');
            replyForm.style.display = 'none';
        });

        // Add reply form listener
        const replyForm = comment.querySelector('.replyForm');
        replyForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            try {
                const data = await makeRequest('<?= base_url('blogs/comment/reply') ?>', formData);
                
                if (data.success) {
                    // Clear form and hide it
                    this.reset();
                    this.closest('.reply-form').style.display = 'none';
                    
                    // Add new reply to the list
                    const repliesList = this.closest('.comment').querySelector('.replies-list');
                    data.reply.forEach(reply => {
                        const replyHtml = `
                            <div class="reply mb-3">
                                <div class="d-flex">
                                    <img src="${reply.image ? '<?= base_url('user-image/') ?>' + reply.image : '<?= base_url('assets/img/default-avatar.svg') ?>'}" 
                                         class="rounded-circle me-3" 
                                         alt="User Avatar"
                                         style="width: 32px; height: 32px;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <h6 class="mb-0 me-2">
                                                ${reply.first_name} ${reply.middle_name} ${reply.last_name}
                                            </h6>
                                            ${reply.user_id === <?= $blog['user_id'] ?> ? '<span class="badge bg-primary">Author</span>' : ''}
                                        </div>
                                        <p class="mb-1">${reply.content}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        repliesList.insertAdjacentHTML('beforeend', replyHtml);
                    });
                } else if (data.error) {
                    alert(data.error);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            } finally {
                submitBtn.disabled = false;
            }
        });
    }
});
</script>

<style>
.blog-reactions {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background-color: var(--bs-light);
    border-radius: 12px;
}

[data-bs-theme="dark"] .blog-reactions {
    background-color: var(--bs-dark);
}

.blog-reaction-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border-radius: 20px;
    padding: 0.5rem 1rem;
    color: var(--bs-body-color);
    border-color: var(--bs-border-color);
}

[data-bs-theme="dark"] .blog-reaction-btn {
    color: var(--bs-light);
    border-color: var(--bs-border-color);
}

.blog-reaction-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background-color: var(--bs-light);
    color: var(--bs-primary);
    border-color: var(--bs-primary);
}

[data-bs-theme="dark"] .blog-reaction-btn:hover {
    background-color: var(--bs-dark);
    color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.blog-reaction-btn i {
    font-size: 1.1rem;
}

.blog-reaction-btn .bi-hand-thumbs-up-fill,
.blog-reaction-btn .bi-hand-thumbs-down-fill {
    color: var(--bs-primary);
}

[data-bs-theme="dark"] .blog-reaction-btn .bi-hand-thumbs-up-fill,
[data-bs-theme="dark"] .blog-reaction-btn .bi-hand-thumbs-down-fill {
    color: var(--bs-primary);
}

.comment {
    padding: 1rem 0;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--bs-border-color);
}

.comment:last-child {
    border-bottom: none;
}

.reply {
    padding: 0.75rem 0;
    margin-bottom: 0.75rem;
    border-bottom: 1px solid var(--bs-border-color);
}

.reply:last-child {
    border-bottom: none;
}

.reply-form {
    margin-top: 1rem;
    padding: 1rem 0;
}

[data-bs-theme="dark"] .comment,
[data-bs-theme="dark"] .reply {
    border-color: var(--bs-border-color);
}

.comments-section {
    margin-top: 3rem;
    padding-top: 2rem;
}

.add-comment {
    margin-bottom: 2rem;
}

.add-comment textarea {
    border: none;
    border-bottom: 1px solid var(--bs-border-color);
    border-radius: 0;
    padding: 0.5rem 0;
    resize: none;
    background: transparent;
}

.add-comment textarea:focus {
    box-shadow: none;
    border-color: var(--bs-primary);
}

.comment-reactions {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 0.5rem;
}

.comment-reactions .btn-link {
    color: var(--bs-body-color);
    text-decoration: none;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.comment-reactions .btn-link:hover {
    color: var(--bs-primary);
    background-color: var(--bs-light);
    border-radius: 4px;
}

.reply-btn {
    color: var(--bs-body-color);
    text-decoration: none;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    transition: all 0.2s ease;
}

.reply-btn:hover {
    color: var(--bs-primary);
    background-color: var(--bs-light);
    border-radius: 4px;
    text-decoration: none;
}

.replies-list {
    margin-top: 1rem;
    padding-left: 1rem;
    border-left: 2px solid var(--bs-border-color);
}

.replies-list .reply {
    margin-left: 1rem;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    border-radius: 4px;
}

.comment img, .reply img {
    border: 2px solid var(--bs-primary);
    transition: all 0.2s ease;
}

.comment:hover img, .reply:hover img {
    transform: scale(1.05);
}

.comment h6, .reply h6 {
    font-weight: 600;
    color: var(--bs-body-color);
}

.comment p, .reply p {
    color: var(--bs-body-color);
    line-height: 1.6;
    margin-top: 0.25rem;
}

.comment small, .reply small {
    font-size: 0.8rem;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .comment {
        padding: 0.75rem 0;
    }
    
    .reply {
        padding: 0.5rem 0;
    }
    
    .replies-list .reply {
        margin-left: 0.5rem;
    }
    
    .add-comment {
        padding: 0;
    }
}
</style>
