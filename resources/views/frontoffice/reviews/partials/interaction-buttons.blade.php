@auth
<meta name="csrf-token" content="{{ csrf_token() }}">  
<div class="flex items-center space-x-4">
        <!-- Helpful Vote Button -->
        <button onclick="voteReview({{ $review->review_id }}, 'helpful_vote')" 
                class="flex items-center space-x-1 text-sm text-gray-600 hover:text-green-600 transition duration-200"
                id="helpful-btn-{{ $review->review_id }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7v13m-3-2l-2 2m0-2v2a2 2 0 002 2h1m-3-2h3"/>
            </svg>
            <span id="helpful-count-{{ $review->review_id }}">{{ $review->helpful_votes }}</span>
        </button>
        
        <!-- Unhelpful Vote Button -->
        <button onclick="voteReview({{ $review->review_id }}, 'unhelpful_vote')" 
                class="flex items-center space-x-1 text-sm text-gray-600 hover:text-red-600 transition duration-200"
                id="unhelpful-btn-{{ $review->review_id }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L15 17V4m-3 2l2-2m0 2v2a2 2 0 01-2 2h-1m3-2h-3"/>
            </svg>
            <span id="unhelpful-count-{{ $review->review_id }}">{{ $review->unhelpful_votes }}</span>
        </button>
        
        <!-- Reply Button -->
        <button onclick="showReplyForm({{ $review->review_id }})" 
                class="flex items-center space-x-1 text-sm text-gray-600 hover:text-blue-600 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span>Reply</span>
        </button>
        
        <!-- Bookmark Button -->
        <button onclick="bookmarkReview({{ $review->review_id }})" 
                class="flex items-center space-x-1 text-sm text-gray-600 hover:text-yellow-600 transition duration-200"
                id="bookmark-btn-{{ $review->review_id }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <span>Save</span>
        </button>
        
        <!-- Share Button -->
        <button onclick="shareReview({{ $review->review_id }})" 
                class="flex items-center space-x-1 text-sm text-gray-600 hover:text-purple-600 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
            </svg>
            <span>Share</span>
        </button>
        
        <!-- Report Button (dropdown) -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="flex items-center space-x-1 text-sm text-gray-600 hover:text-red-600 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <span>Report</span>
            </button>
            
            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 border">
                <button onclick="reportReview({{ $review->review_id }}, 'inappropriate')" 
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Inappropriate content
                </button>
                <button onclick="reportReview({{ $review->review_id }}, 'spam')" 
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Spam
                </button>
                <button onclick="reportReview({{ $review->review_id }}, 'fake')" 
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Fake review
                </button>
                <button onclick="reportReview({{ $review->review_id }}, 'other')" 
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Other
                </button>
            </div>
        </div>
    </div>
    
    <!-- Reply Form (hidden by default) -->
    <div id="reply-form-{{ $review->review_id }}" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
        <form onsubmit="submitReply(event, {{ $review->review_id }})">
            @csrf
            <div class="mb-3">
                <textarea name="content" 
                          placeholder="Write your reply..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                          rows="3" 
                          required></textarea>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-black text-sm rounded-md hover:bg-blue-700 transition duration-200">
                    Post Reply
                </button>
                <button type="button" 
                        onclick="hideReplyForm({{ $review->review_id }})"
                        class="px-4 py-2 text-gray-600 text-sm hover:text-gray-800 transition duration-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>

@else
    <!-- Login prompt for non-authenticated users -->
    <div class="flex items-center space-x-4 text-sm text-gray-500">
        <span>{{ $review->helpful_votes }} helpful</span>
        <span>{{ $review->unhelpful_votes }} not helpful</span>
        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login to interact</a>
    </div>
@endauth

@push('scripts')
<script>
// CSRF Token for API requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

async function voteReview(reviewId, voteType) {
    try {
        const response = await fetch(`/reviews/${reviewId}/interactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                interaction_type: voteType
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            // Update vote counts
            updateVoteCounts(reviewId);
            
            // Show feedback
            showNotification(data.message, 'success');
            
            // Update button states
            updateVoteButtonStates(reviewId, voteType, data.action);
        } else {
            showNotification(data.error || 'Error voting on review', 'error');
        }
    } catch (error) {
        showNotification('Network error. Please try again.', 'error');
    }
}

async function bookmarkReview(reviewId) {
    try {
        const response = await fetch(`/reviews/${reviewId}/interactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                interaction_type: 'bookmark'
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            const bookmarkBtn = document.getElementById(`bookmark-btn-${reviewId}`);
            if (data.action === 'created') {
                bookmarkBtn.classList.add('text-yellow-600');
                showNotification('Review bookmarked!', 'success');
            } else {
                bookmarkBtn.classList.remove('text-yellow-600');
                showNotification('Bookmark removed', 'info');
            }
        } else {
            showNotification(data.error || 'Error bookmarking review', 'error');
        }
    } catch (error) {
        showNotification('Network error. Please try again.', 'error');
    }
}

function showReplyForm(reviewId) {
    const form = document.getElementById(`reply-form-${reviewId}`);
    form.classList.remove('hidden');
    form.querySelector('textarea').focus();
}

function hideReplyForm(reviewId) {
    const form = document.getElementById(`reply-form-${reviewId}`);
    form.classList.add('hidden');
    form.querySelector('textarea').value = '';
}

async function submitReply(event, reviewId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const content = formData.get('content');
    
    if (!content.trim()) {
        showNotification('Please enter a reply', 'error');
        return;
    }
    
    try {
        const response = await fetch(`/reviews/${reviewId}/interactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                interaction_type: 'reply',
                content: content,
                parent_interaction_id: null // Top-level reply
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            showNotification('Reply posted successfully!', 'success');
            hideReplyForm(reviewId);
            
            // Refresh discussions if they're visible
            const discussionsContainer = document.getElementById(`discussions-${reviewId}`);
            if (discussionsContainer && !discussionsContainer.classList.contains('hidden')) {
                toggleDiscussions(reviewId); // This will refresh the discussions
                toggleDiscussions(reviewId);
            }
            
            // Update reply count
            updateReplyCount(reviewId);
        } else {
            showNotification(data.error || 'Error posting reply', 'error');
        }
    } catch (error) {
        showNotification('Network error. Please try again.', 'error');
    }
}

async function shareReview(reviewId) {
    const url = `${window.location.origin}/reviews/${reviewId}`;
    
    if (navigator.share) {
        try {
            await navigator.share({
                title: 'Book Review',
                url: url
            });
        } catch (error) {
            // Fallback to clipboard
            copyToClipboard(url);
        }
    } else {
        copyToClipboard(url);
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Link copied to clipboard!', 'success');
    }).catch(() => {
        showNotification('Unable to copy link', 'error');
    });
}

async function reportReview(reviewId, reason) {
    const customReason = reason === 'other' ? prompt('Please describe the issue:') : reason;
    
    if (!customReason) return;
    
    try {
        const response = await fetch(`/reviews/${reviewId}/interactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                interaction_type: 'report',
                content: `Reason: ${customReason}`
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            showNotification('Report submitted. Thank you for helping keep our community safe.', 'success');
        } else {
            showNotification(data.error || 'Error submitting report', 'error');
        }
    } catch (error) {
        showNotification('Network error. Please try again.', 'error');
    }
}

async function updateVoteCounts(reviewId) {
    try {
        const response = await fetch(`/reviews/${reviewId}/vote-stats`);
        const data = await response.json();
        
        document.getElementById(`helpful-count-${reviewId}`).textContent = data.helpful_votes;
        document.getElementById(`unhelpful-count-${reviewId}`).textContent = data.unhelpful_votes;
    } catch (error) {
        console.error('Error updating vote counts:', error);
    }
}

function updateVoteButtonStates(reviewId, voteType, action) {
    const helpfulBtn = document.getElementById(`helpful-btn-${reviewId}`);
    const unhelpfulBtn = document.getElementById(`unhelpful-btn-${reviewId}`);
    
    // Reset button states
    helpfulBtn.classList.remove('text-green-600');
    unhelpfulBtn.classList.remove('text-red-600');
    
    // Apply active state if vote was created/updated
    if (action === 'created' || action === 'updated') {
        if (voteType === 'helpful_vote') {
            helpfulBtn.classList.add('text-green-600');
        } else if (voteType === 'unhelpful_vote') {
            unhelpfulBtn.classList.add('text-red-600');
        }
    }
}

function updateReplyCount(reviewId) {
    // This would update the reply count display
    // Implementation depends on your specific UI
}

function showNotification(message, type = 'info') {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg text-black z-50 ${
        type === 'success' ? 'bg-green-600' :
        type === 'error' ? 'bg-red-600' :
        type === 'warning' ? 'bg-yellow-600' :
        'bg-blue-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush