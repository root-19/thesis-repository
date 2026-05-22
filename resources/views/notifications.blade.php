<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-[#252422]">Notifications</h3>
                        <p class="text-sm text-[#CCC5B9] mt-1">{{ $notifications->total() }} notifications</p>
                    </div>
                    @if (auth()->user()->notifications()->where('read_at', null)->count() > 0)
                        <form method="POST" action="{{ route('notifications.mark-read') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg bg-[#EB5E28] text-white text-sm font-medium hover:bg-[#d45220] transition-colors">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>

                <div class="divide-y divide-[#CCC5B9]/20">
                    @forelse($notifications as $notification)
                        <div class="p-6 hover:bg-[#FFFCF2] transition-colors {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    @if ($notification->read_at)
                                        <div class="h-10 w-10 rounded-full bg-[#CCC5B9]/20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#CCC5B9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-[#EB5E28] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-[#252422]">{{ $notification->data['message'] }}</p>
                                    @if (isset($notification->data['thesis_id']))
                                        @php
                                            $thesis = \App\Models\Thesis::find($notification->data['thesis_id']);
                                        @endphp
                                        @if ($thesis)
                                            <div class="mt-3 p-3 bg-[#FFFCF2] rounded-lg border border-[#CCC5B9]/20">
                                                <p class="text-sm font-semibold text-[#252422]">{{ $thesis->title }}</p>
                                                <p class="text-xs text-[#CCC5B9] mt-1">{{ $thesis->author }}</p>
                                            </div>
                                        @endif
                                    @endif
                                    <p class="text-xs text-[#CCC5B9] mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if (!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.mark-read.single', $notification) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-[#FFFCF2] text-[#403D39] hover:bg-[#EB5E28] hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if (isset($notification->data['thesis_id']))
                                        <button onclick="showThesisModal({{ $notification->data['thesis_id'] }}{{ isset($notification->data['comment_id']) ? ', ' . $notification->data['comment_id'] : '' }})" class="p-2 rounded-lg bg-[#FFFCF2] text-[#403D39] hover:bg-[#EB5E28] hover:text-white transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </button>
                                    @elseif (isset($notification->data['application_id']))
                                        <a href="{{ route('co-author-applications.index') }}" target="_blank" class="p-2 rounded-lg bg-[#FFFCF2] text-[#403D39] hover:bg-[#EB5E28] hover:text-white transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @elseif (isset($notification->data['sender_id']))
                                        <a href="{{ route('user.messages.show', \App\Models\User::find($notification->data['sender_id'])) }}" target="_blank" class="p-2 rounded-lg bg-[#FFFCF2] text-[#403D39] hover:bg-[#EB5E28] hover:text-white transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#CCC5B9] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p class="text-sm text-[#CCC5B9]">No notifications</p>
                        </div>
                    @endforelse
                </div>

                @if ($notifications->hasPages())
                    <div class="p-6 border-t border-[#CCC5B9]/20">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CCC5B9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #EB5E28;
        }
    </style>

    <!-- Thesis Modal -->
    <div id="thesisModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/50" onclick="closeThesisModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full h-[90vh] flex flex-col">
                <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-xl font-bold text-[#252422]">Thesis Details</h3>
                    <button onclick="closeThesisModal()" class="p-2 rounded-lg hover:bg-[#FFFCF2] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#403D39]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="thesisModalContent" class="p-6 overflow-y-auto flex-1 custom-scrollbar min-h-0">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showThesisModal(thesisId, commentId = null) {
            const modal = document.getElementById('thesisModal');
            const content = document.getElementById('thesisModalContent');

            content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#EB5E28] mx-auto"></div></div>';
            modal.classList.remove('hidden');

            fetch(`/theses/${thesisId}/details`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = `
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-lg font-bold text-[#252422]">${data.title}</h4>
                                <p class="text-sm text-[#CCC5B9] mt-1">By ${data.author}</p>
                                <p class="text-sm text-[#CCC5B9]">${data.thesis_date}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#403D39]">${data.description}</p>
                            </div>
                            @if (auth()->check())
                            <div class="pt-4 border-t border-[#CCC5B9]/20">
                                <form method="POST" action="{{ route('theses.comments.store', ':thesisId') }}" id="commentForm" onsubmit="submitComment(event, ${thesisId})">
                                    @csrf
                                    <div class="flex gap-3">
                                        @if (auth()->user()->profile_image_path)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_image_path) }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-full object-cover">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                                <span class="text-xs font-semibold text-[#403D39]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <input type="text" name="comment" placeholder="Write a comment..."
                                                class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28]/10" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                            <div id="commentsContainer" class="space-y-3 mt-4">
                                <!-- Comments will be loaded here -->
                            </div>
                        </div>
                    `;

                    // Load comments
                    loadComments(thesisId, commentId);
                })
                .catch(error => {
                    content.innerHTML = '<p class="text-red-500 text-center">Error loading thesis details.</p>';
                });
        }

        function closeThesisModal() {
            document.getElementById('thesisModal').classList.add('hidden');
        }

        function loadComments(thesisId, commentId = null) {
            fetch(`/theses/${thesisId}/comments-json`)
                .then(response => response.json())
                .then(comments => {
                    const container = document.getElementById('commentsContainer');
                    if (comments.length === 0) {
                        container.innerHTML = '<p class="text-sm text-[#CCC5B9] text-center">No comments yet.</p>';
                        return;
                    }

                    container.innerHTML = comments.map(comment => `
                        <div class="flex gap-3 p-3 bg-[#FFFCF2] rounded-lg" id="comment-${comment.id}">
                            ${comment.user.profile_image_path
                                ? `<img src="/storage/${comment.user.profile_image_path}" alt="${comment.user.name}" class="h-8 w-8 rounded-full object-cover">`
                                : `<div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                    <span class="text-xs font-semibold text-[#403D39]">${comment.user.name.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div class="flex-1">
                                <div class="bg-[#FFFCF2] rounded-xl p-3">
                                    <p class="text-sm font-semibold text-[#252422]">${comment.user.name}</p>
                                    <p class="text-sm text-[#403D39]">${comment.comment}</p>
                                </div>
                                <div class="flex items-center gap-4 mt-1 ml-2">
                                    <button onclick="toggleReplyForm(${comment.id}, ${thesisId})" class="text-xs text-[#CCC5B9] hover:text-[#252422]">Reply</button>
                                </div>
                                <!-- Reply Form -->
                                <div id="reply-form-${comment.id}" class="hidden mt-2 ml-4">
                                    <form onsubmit="submitReply(event, ${thesisId}, ${comment.id})">
                                        <div class="flex gap-2">
                                            <input type="text" name="comment" placeholder="Write a reply..."
                                                class="flex-1 px-3 py-2 rounded-lg border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28]/10" required>
                                            <button type="submit" class="px-3 py-2 rounded-lg bg-[#EB5E28] text-white text-xs font-medium hover:bg-[#d45220] transition-colors">Reply</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Replies Section -->
                                <div id="replies-${comment.id}" class="mt-2 ml-4 space-y-2">
                                    <!-- Replies will be loaded here -->
                                </div>
                            </div>
                        </div>
                    `).join('');

                    // Load replies for each comment
                    comments.forEach(comment => {
                        loadReplies(thesisId, comment.id);
                    });

                    // Scroll to specific comment if provided
                    if (commentId) {
                        setTimeout(() => {
                            const commentElement = document.getElementById(`comment-${commentId}`);
                            if (commentElement) {
                                commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                commentElement.classList.add('bg-[#EB5E28]/10');
                                setTimeout(() => {
                                    commentElement.classList.remove('bg-[#EB5E28]/10');
                                }, 2000);
                            }
                        }, 300);
                    }
                });
        }

        function loadReplies(thesisId, commentId) {
            fetch(`/theses/${thesisId}/replies-json/${commentId}`)
                .then(response => response.json())
                .then(replies => {
                    const container = document.getElementById(`replies-${commentId}`);
                    if (replies.length === 0) {
                        return;
                    }

                    container.innerHTML = replies.map(reply => `
                        <div class="flex gap-2 p-2 bg-white rounded-lg border border-[#CCC5B9]/10">
                            ${reply.user.profile_image_path
                                ? `<img src="/storage/${reply.user.profile_image_path}" alt="${reply.user.name}" class="h-6 w-6 rounded-full object-cover">`
                                : `<div class="h-6 w-6 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                    <span class="text-xs font-semibold text-[#403D39]">${reply.user.name.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-[#252422]">${reply.user.name}</p>
                                <p class="text-xs text-[#403D39]">${reply.comment}</p>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(error => {
                    console.error('Error loading replies:', error);
                });
        }

        function toggleReplyForm(commentId, thesisId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.classList.toggle('hidden');
        }

        function submitReply(event, thesisId, parentCommentId) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            formData.append('parent_id', parentCommentId);

            fetch(`{{ route('theses.comments.store', ':thesisId') }}`.replace(':thesisId', thesisId), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    form.classList.add('hidden');
                    loadComments(thesisId);
                }
            })
            .catch(error => {
                console.error('Error submitting reply:', error);
            });
        }

        function submitComment(event, thesisId) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            form.action = form.action.replace(':thesisId', thesisId);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    loadComments(thesisId);
                }
            })
            .catch(error => {
                console.error('Error submitting comment:', error);
            });
        }
    </script>
</x-app-layout>
