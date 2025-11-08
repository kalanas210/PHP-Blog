// Rich Text Editor with Preview using Quill.js

document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    if (!contentTextarea) return;

    // Create editor container
    const editorContainer = document.createElement('div');
    editorContainer.id = 'editor-container';
    editorContainer.className = 'border border-gray-300 rounded bg-white';
    editorContainer.style.minHeight = '400px';
    
    // Insert editor before textarea
    contentTextarea.parentNode.insertBefore(editorContainer, contentTextarea);
    
    // Hide original textarea but keep it for form submission
    contentTextarea.style.display = 'none';
    
    // Initialize Quill editor
    const quill = new Quill('#editor-container', {
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ],
                handlers: {
                    'image': imageHandler
                }
            }
        },
        theme: 'snow',
        placeholder: 'Start writing your post content...'
    });
    
    // Image handler for Quill
    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        
        input.onchange = function() {
            const file = input.files[0];
            if (file) {
                // Check file size (5MB max)
            if (file.size > 5242880) {
                alert('Image size must be less than 5MB');
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Only JPG, PNG, GIF, and WebP images are allowed');
                return;
            }
            
            // Create FormData
            const formData = new FormData();
            formData.append('image', file);
            
            // Show loading
            const range = quill.getSelection();
            const index = range ? range.index : quill.getLength();
            quill.insertText(index, 'Uploading image...', 'user');
            quill.setSelection(index + 19);
            
            // Upload image
            fetch(window.SITE_URL + '/api/upload-image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.url) {
                    // Remove "Uploading image..." text
                    quill.deleteText(index, 19);
                    // Insert image
                    quill.insertEmbed(index, 'image', data.url);
                } else {
                    quill.deleteText(index, 19);
                    alert(data.error || 'Failed to upload image');
                }
            })
            .catch(error => {
                quill.deleteText(index, 19);
                alert('Error uploading image: ' + error.message);
            });
            }
        };
    }
    
    // Set initial content if editing
    if (contentTextarea.value) {
        quill.root.innerHTML = contentTextarea.value;
    }
    
    // Update hidden textarea on content change
    quill.on('text-change', function() {
        contentTextarea.value = quill.root.innerHTML;
    });
    
    // Also update on selection change (for paste, etc.)
    quill.on('selection-change', function() {
        contentTextarea.value = quill.root.innerHTML;
    });
    
    // Preview functionality
    const previewContainer = document.createElement('div');
    previewContainer.id = 'preview-container';
    previewContainer.className = 'hidden border border-gray-300 rounded bg-white p-6 mt-4';
    previewContainer.style.minHeight = '400px';
    previewContainer.style.maxHeight = '600px';
    previewContainer.style.overflowY = 'auto';
    
    // Add preview button to toolbar
    setTimeout(function() {
        const toolbar = document.querySelector('.ql-toolbar');
        if (toolbar) {
            const previewBtn = document.createElement('button');
            previewBtn.type = 'button';
            previewBtn.className = 'ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm';
            previewBtn.innerHTML = '<i class="fas fa-eye mr-2"></i>Preview';
            previewBtn.addEventListener('click', function() {
                const isPreviewVisible = !previewContainer.classList.contains('hidden');
                if (isPreviewVisible) {
                    previewContainer.classList.add('hidden');
                    previewBtn.innerHTML = '<i class="fas fa-eye mr-2"></i>Preview';
                    editorContainer.style.display = 'block';
                } else {
                    previewContainer.classList.remove('hidden');
                    previewBtn.innerHTML = '<i class="fas fa-edit mr-2"></i>Edit';
                    editorContainer.style.display = 'none';
                    // Update preview content
                    previewContainer.innerHTML = quill.root.innerHTML;
                }
            });
            toolbar.appendChild(previewBtn);
        }
    }, 100);
    
    // Insert preview container after editor container
    editorContainer.parentNode.insertBefore(previewContainer, editorContainer.nextSibling);
    
    // Ensure content is synced before form submission
    const form = contentTextarea.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            contentTextarea.value = quill.root.innerHTML;
        });
    }
});

