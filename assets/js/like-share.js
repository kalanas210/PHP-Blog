// Like and Share functionality

document.addEventListener('DOMContentLoaded', function() {
    // Like button
    const likeBtn = document.getElementById('like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            
            // Check if user is logged in
            const baseUrl = window.SITE_URL || '';
            fetch(baseUrl + '/includes/auth-check.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.logged_in) {
                        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
                        return;
                    }
                    
                    // Toggle like
                    const formData = new FormData();
                    formData.append('post_id', postId);
                    
                    fetch(baseUrl + '/api/like.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const likesCount = document.getElementById('likes-count');
                            if (likesCount) {
                                likesCount.textContent = data.likes_count;
                            }
                            
                            // Update button appearance
                            const icon = likeBtn.querySelector('i');
                            if (data.liked) {
                                likeBtn.classList.remove('bg-gray-100', 'text-gray-700');
                                likeBtn.classList.add('bg-red-100', 'text-red-600');
                                if (icon) {
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                }
                            } else {
                                likeBtn.classList.remove('bg-red-100', 'text-red-600');
                                likeBtn.classList.add('bg-gray-100', 'text-gray-700');
                                if (icon) {
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                }
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                });
        });
    }
    
    // Share button
    const shareBtn = document.getElementById('share-btn');
    const shareUrlContainer = document.getElementById('share-url-container');
    const shareUrlInput = document.getElementById('share-url');
    
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            const slug = this.getAttribute('data-post-slug');
            
            const formData = new FormData();
            formData.append('slug', slug);
            
            const baseUrl = window.SITE_URL || '';
            fetch(baseUrl + '/api/share.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (shareUrlInput) {
                        shareUrlInput.value = data.url;
                    }
                    if (shareUrlContainer) {
                        shareUrlContainer.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
    
    // Comment form
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(commentForm);
            
            const baseUrl = window.SITE_URL || '';
            fetch(baseUrl + '/api/comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add comment to list
                    const commentsList = document.getElementById('comments-list');
                    if (commentsList && data.comment) {
                        const commentDiv = document.createElement('div');
                        commentDiv.className = 'border-b border-gray-200 pb-4';
                        
                        const profilePhoto = data.comment.profile_photo || 'default-avatar.png';
                        const name = data.comment.name || data.comment.username || 'Anonymous';
                        
                        const escapedContent = data.comment.content.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
                        const escapedName = name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        commentDiv.innerHTML = `
                            <div class="flex items-start gap-4">
                                <img src="${baseUrl}/assets/images/${profilePhoto}" 
                                     alt="${escapedName}"
                                     class="w-10 h-10 rounded-full object-cover"
                                     onerror="this.src='${baseUrl}/assets/images/default-avatar.png'">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h4 class="font-semibold text-gray-900">${escapedName}</h4>
                                        <span class="text-xs text-gray-500">Just now</span>
                                    </div>
                                    <p class="text-gray-700">${escapedContent}</p>
                                </div>
                            </div>
                        `;
                        
                        // Remove "No comments yet" message if exists
                        const noComments = commentsList.querySelector('p');
                        if (noComments && noComments.textContent.includes('No comments')) {
                            noComments.remove();
                        }
                        
                        commentsList.appendChild(commentDiv);
                        
                        // Update comment count
                        const commentCount = document.querySelector('h3');
                        if (commentCount) {
                            const match = commentCount.textContent.match(/(\d+)/);
                            const currentCount = match ? parseInt(match[1]) : 0;
                            commentCount.textContent = `Comments (${currentCount + 1})`;
                        }
                    }
                    
                    commentForm.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});
