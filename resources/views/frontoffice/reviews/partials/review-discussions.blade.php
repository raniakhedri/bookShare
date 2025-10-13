<!-- Review Discussions Partial -->
 <meta name="csrf-token" content="{{ csrf_token() }}"> 
@if($discussions && $discussions->count() > 0)
    <div class="space-y-4">
        @foreach($discussions as $discussion)
            <div class="border-l-2 border-gray-200 pl-4">
                <!-- Discussion Header -->
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($discussion->user->name, 0, 1) }}
                    </div>
                    <div>
                        <span class="font-medium text-gray-900 text-sm">{{ $discussion->user->name }}</span>
                        <span class="text-gray-500 text-xs ml-2">{{ $discussion->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                
                <!-- Discussion Content -->
                <div class="text-gray-700 text-sm mb-3 pl-11">
                    <p class="whitespace-pre-wrap">{{ $discussion->content }}</p>
                </div>
                
                <!-- Reply Actions -->
                @auth
                    <div class="pl-11">
                        <button onclick="showNestedReplyForm({{ $discussion->interaction_id }})" 
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Reply
                        </button>
                    </div>
                    
                    <!-- Nested Reply Form -->
                    <div id="nested-reply-form-{{ $discussion->interaction_id }}" class="hidden mt-3 pl-11">
                        <form onsubmit="submitNestedReply(event, {{ $review->review_id }}, {{ $discussion->interaction_id }})">
                            @csrf
                            <textarea name="content" 
                                      placeholder="Write your reply..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none text-sm"
                                      rows="2" 
                                      required></textarea>
                            <div class="flex items-center space-x-2 mt-2">
                                <button type="submit" 
                                        class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">
                                    Reply
                                </button>
                                <button type="button" 
                                        onclick="hideNestedReplyForm({{ $discussion->interaction_id }})"
                                        class="px-3 py-1 text-gray-600 text-xs hover:text-gray-800">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @endauth
                
                <!-- Nested Replies -->
                @if($discussion->childInteractions && $discussion->childInteractions->count() > 0)
                    <div class="mt-4 pl-11 space-y-3">
                        @foreach($discussion->childInteractions as $reply)
                            <div class="border-l border-gray-100 pl-3">
                                <div class="flex items-center space-x-2 mb-1">
                                    <div class="w-6 h-6 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900 text-xs">{{ $reply->user->name }}</span>
                                    <span class="text-gray-500 text-xs">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-gray-600 text-xs pl-8">
                                    <p class="whitespace-pre-wrap">{{ $reply->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8 text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <p class="text-sm">No discussions yet.</p>
        @auth
            <p class="text-xs mt-1">Be the first to start a conversation!</p>
        @else
            <p class="text-xs mt-1">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a> to join the discussion.
            </p>
        @endauth
    </div>
@endif

<!-- Load More Button -->
@if(isset($hasMore) && $hasMore)
    <div class="text-center pt-4">
        <button onclick="loadMoreDiscussions({{ $review->review_id }})" 
                id="loadMoreBtn"
                class="px-4 py-2 text-sm text-blue-600 hover:text-blue-800 border border-blue-300 rounded-md hover:bg-blue-50 transition duration-200">
            Load More Discussions
        </button>
    </div>
@endif

@push('scripts')
<script>
// CSRF Token for API requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function showNestedReplyForm(interactionId) {
    const form = document.getElementById(`nested-reply-form-${interactionId}`);
    if (form) {
        form.classList.remove('hidden');
        form.querySelector('textarea').focus();
    }
}

function hideNestedReplyForm(interactionId) {
    const form = document.getElementById(`nested-reply-form-${interactionId}`);
    if (form) {
        form.classList.add('hidden');
        form.querySelector('textarea').value = '';
    }
}

async function submitNestedReply(event, reviewId, parentInteractionId) {
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
                parent_interaction_id: parentInteractionId
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            showNotification('Reply posted successfully!', 'success');
            hideNestedReplyForm(parentInteractionId);
            
            // Refresh the discussions
            await refreshDiscussions(reviewId);
        } else {
            showNotification(data.error || 'Error posting reply', 'error');
        }
    } catch (error) {
        console.error('Network error:', error);
        showNotification('Network error. Please try again.', 'error');
    }
}

async function loadMoreDiscussions(reviewId, offset = 10) {
    try {
        const response = await fetch(`/reviews/${reviewId}/discussions?offset=${offset}`);
        const data = await response.json();
        
        if (response.ok && data.length > 0) {
            // Append new discussions to the existing ones
            // This would require additional server-side pagination logic
            console.log('More discussions loaded:', data);
        } else {
            document.getElementById('loadMoreBtn').style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading more discussions:', error);
    }
}

async function refreshDiscussions(reviewId) {
    try {
        const response = await fetch(`/reviews/${reviewId}/discussions`);
        const discussions = await response.json();
        
        if (response.ok) {
            // Re-render the discussions section
            // This would require a more sophisticated approach in a real application
            location.reload(); // Simple approach for now
        }
    } catch (error) {
        console.error('Error refreshing discussions:', error);
    }
}

function showNotification(message, type = 'info') {
    // Create and show notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
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