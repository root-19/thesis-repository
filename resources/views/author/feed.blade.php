<x-app-layout>

    <div class="min-h-screen bg-[#FFFCF2]">
        <!-- Hero Section -->
        <div class="bg-gradient-to-b from-white to-[#FFFCF2] py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <!-- Badge -->
                   

                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-[#252422] mb-4">
                        Discover Knowledge.<br>
                        <span class="text-[#EB5E28]">Explore Research.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-[#403D39] leading-relaxed max-w-3xl mx-auto mb-8">
                        Browse through thesis papers, capstone projects, and scholarly documents. Search by title, author, department, or keyword to find the research you need.
                    </p>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <div class="flex items-center gap-2 text-[#403D39]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-semibold">{{ $stats['total'] }} Thesis Documents</span>
                        </div>
                        <div class="flex items-center gap-2 text-[#403D39]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#403D39]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold">{{ $stats['years'] }} Years of Research</span>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="max-w-2xl mx-auto">
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Search thesis by title, author, or department..."
                                class="w-full px-6 py-4 rounded-2xl border-2 border-[#CCC5B9]/40 bg-white text-[#252422] placeholder-[#CCC5B9] focus:border-[#EB5E28] focus:ring-4 focus:ring-[#EB5E28]/10 transition-all text-lg shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-6 top-1/2 transform -translate-y-1/2 h-6 w-6 text-[#CCC5B9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <!-- Search Recommendations -->
                            <div id="search-recommendations" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-lg border border-[#CCC5B9]/20 hidden z-50 max-h-96 overflow-y-auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Theses Feed -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <h2 class="text-2xl font-bold text-[#252422]">Thesis Feed</h2>
                <div class="text-[#CCC5B9] text-sm">
                    {{ $theses->count() }} thesis documents
                </div>
            </div>

            <div id="theses-feed" class="max-w-3xl mx-auto space-y-6">
                @forelse($theses as $thesis)
                    <div class="thesis-feed-item bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden"
                         data-title="{{ strtolower($thesis->title) }}"
                         data-author="{{ strtolower($thesis->author) }}"
                         data-uploader="{{ strtolower($thesis->user->name) }}"
                         data-keywords="{{ strtolower($thesis->keywords ?? '') }}">
                        <!-- Header with uploader and author info -->
                        <div class="p-4 border-b border-[#CCC5B9]/20 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if ($thesis->user->profile_image_path)
                                    <img src="{{ asset('storage/' . $thesis->user->profile_image_path) }}" alt="{{ $thesis->user->name }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                        <span class="text-sm font-semibold text-[#403D39]">{{ strtoupper(substr($thesis->user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-[#252422]">{{ $thesis->user->name }}</p>
                                    <p class="text-xs text-[#CCC5B9]">Uploaded · {{ $thesis->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if ($thesis->coAuthors->count() > 0)
                                    @php $firstAuthor = $thesis->coAuthors->first(); @endphp
                                    @if ($firstAuthor->profile_image_path)
                                        <img src="{{ asset('storage/' . $firstAuthor->profile_image_path) }}" alt="{{ $firstAuthor->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                            <span class="text-sm font-semibold text-[#403D39]">{{ strtoupper(substr($firstAuthor->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="h-10 w-10 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                        <span class="text-sm font-semibold text-[#403D39]">{{ strtoupper(substr($thesis->author, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-[#252422]">{{ $thesis->author }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#252422] mb-2">{{ $thesis->title }}</h3>
                            <p class="text-sm text-[#403D39] mb-3">{{ $thesis->description }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <div class="flex items-center gap-2 bg-[#FFFCF2] rounded-lg px-3 py-2 border border-[#CCC5B9]/20">
                                    <div class="h-5 w-5 rounded-full bg-[#EB5E28] flex items-center justify-center">
                                        <span class="text-xs font-semibold text-white">{{ strtoupper(substr($thesis->author, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-[#252422]">{{ $thesis->author }}</p>
                                        <p class="text-xs text-[#EB5E28]">Primary Author</p>
                                    </div>
                                </div>
                                @foreach ($thesis->coAuthors as $coAuthor)
                                    <div class="flex items-center gap-2 bg-[#FFFCF2] rounded-lg px-3 py-2 border border-[#CCC5B9]/20">
                                        @if ($coAuthor->profile_image_path)
                                            <img src="{{ asset('storage/' . $coAuthor->profile_image_path) }}" alt="{{ $coAuthor->name }}" class="h-5 w-5 rounded-full object-cover">
                                        @else
                                            <div class="h-5 w-5 rounded-full bg-[#403D39] flex items-center justify-center">
                                                <span class="text-xs font-semibold text-white">{{ strtoupper(substr($coAuthor->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-xs font-medium text-[#252422]">{{ $coAuthor->name }}</p>
                                            <p class="text-xs text-[#CCC5B9]">Co-Author</p>
                                        </div>
                                    </div>
                                @endforeach
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-[#FFFCF2] text-[#403D39]">
                                    {{ $thesis->thesis_date->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- PDF Preview -->
                            <div class="bg-[#FFFCF2] rounded-xl p-4 flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-[#252422]">Thesis Document</p>
                                    <p class="text-xs text-[#CCC5B9]">PDF file</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $thesis->pdf_file_path) }}" target="_blank" class="px-4 py-2 rounded-full bg-[#EB5E28] text-white text-sm font-medium hover:bg-[#d45220] transition-colors">
                                        View PDF
                                    </a>
                                    <a href="{{ asset('storage/' . $thesis->pdf_file_path) }}" download class="px-4 py-2 rounded-full bg-[#403D39] text-white text-sm font-medium hover:bg-[#252422] transition-colors">
                                        Download
                                    </a>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-4 pt-4 border-t border-[#CCC5B9]/20">
                                <form method="POST" action="{{ route('theses.reaction.toggle', $thesis) }}">
                                    @csrf
                                    @if ($thesis->reactions->where('user_id', auth()->id())->first())
                                        <button type="submit" class="flex items-center gap-2 text-[#EB5E28] hover:text-[#d45220] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            {{ $thesis->reactions->count() }}
                                        </button>
                                    @else
                                        <button type="submit" class="flex items-center gap-2 text-[#CCC5B9] hover:text-[#EB5E28] transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            {{ $thesis->reactions->count() }}
                                        </button>
                                    @endif
                                </form>
                                <button onclick="toggleComments({{ $thesis->id }})" class="flex items-center gap-2 text-[#CCC5B9] hover:text-[#252422] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span class="text-sm font-medium">{{ $thesis->comments->count() }}</span>
                                </button>
                            </div>

                            <!-- Comments Section -->
                            <div id="comments-{{ $thesis->id }}" class="hidden mt-4 pt-4 border-t border-[#CCC5B9]/20">
                                <!-- Comment Form -->
                                <form method="POST" action="{{ route('theses.comments.store', $thesis) }}" class="mb-4">
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

                                <!-- Comments List -->
                                <div class="space-y-3">
                                    @forelse($thesis->comments->where('parent_id', null) as $comment)
                                        <div class="flex gap-3">
                                            @if ($comment->user->profile_image_path)
                                                <img src="{{ asset('storage/' . $comment->user->profile_image_path) }}" alt="{{ $comment->user->name }}" class="h-8 w-8 rounded-full object-cover">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                                    <span class="text-xs font-semibold text-[#403D39]">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <div class="bg-[#FFFCF2] rounded-xl p-3">
                                                    <p class="text-sm font-semibold text-[#252422]">{{ $comment->user->name }}</p>
                                                    <p class="text-sm text-[#403D39]">{{ $comment->comment }}</p>
                                                </div>
                                                <div class="flex items-center gap-4 mt-1 ml-2">
                                                    <button onclick="toggleReplyForm({{ $comment->id }})" class="text-xs text-[#CCC5B9] hover:text-[#252422]">Reply</button>
                                                    @if ($comment->user_id === auth()->id())
                                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs text-[#CCC5B9] hover:text-[#EB5E28]">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <!-- Reply Form -->
                                                <div id="reply-form-{{ $comment->id }}" class="hidden mt-2 ml-2">
                                                    <form method="POST" action="{{ route('theses.comments.store', $thesis) }}">
                                                        @csrf
                                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                        <div class="flex gap-2">
                                                            <input type="text" name="comment" placeholder="Write a reply..."
                                                                class="flex-1 px-3 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder-[#CCC5B9] text-xs focus:border-[#EB5E28] focus:ring-[#EB5E28]/10" required>
                                                            <button type="submit" class="px-3 py-2 rounded-lg bg-[#EB5E28] text-white text-xs font-medium hover:bg-[#d45220] transition-colors">Reply</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Replies -->
                                                @if ($comment->replies->count() > 0)
                                                    <button onclick="toggleReplies({{ $comment->id }})" class="text-xs text-[#EB5E28] hover:text-[#d45220] mt-1 ml-2">
                                                        View {{ $comment->replies->count() }} {{ $comment->replies->count() === 1 ? 'reply' : 'replies' }}
                                                    </button>
                                                    <div id="replies-{{ $comment->id }}" class="hidden mt-3 space-y-2">
                                                        @foreach ($comment->replies as $reply)
                                                            <div class="flex gap-2">
                                                                @if ($reply->user->profile_image_path)
                                                                    <img src="{{ asset('storage/' . $reply->user->profile_image_path) }}" alt="{{ $reply->user->name }}" class="h-6 w-6 rounded-full object-cover">
                                                                @else
                                                                    <div class="h-6 w-6 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                                                        <span class="text-xs font-semibold text-[#403D39]">{{ strtoupper(substr($reply->user->name, 0, 1)) }}</span>
                                                                    </div>
                                                                @endif
                                                                <div class="flex-1">
                                                                    <div class="bg-[#FFFCF2] rounded-xl p-2">
                                                                        <p class="text-xs font-semibold text-[#252422]">{{ $reply->user->name }}</p>
                                                                        <p class="text-xs text-[#403D39]">{{ $reply->comment }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-[#CCC5B9] text-center">No comments yet</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-[#CCC5B9] text-lg">No thesis uploaded yet</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        let searchRecommendationsTimeout;

        document.getElementById('search-input').addEventListener('input', function(e) {
            filterTheses();
            
            // Show search recommendations
            clearTimeout(searchRecommendationsTimeout);
            const searchTerm = e.target.value.trim();
            
            if (searchTerm.length >= 2) {
                searchRecommendationsTimeout = setTimeout(() => {
                    fetchSearchRecommendations(searchTerm);
                }, 300);
            } else {
                document.getElementById('search-recommendations').classList.add('hidden');
            }
        });

        // Hide recommendations when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#search-input') && !e.target.closest('#search-recommendations')) {
                document.getElementById('search-recommendations').classList.add('hidden');
            }
        });

        function fetchSearchRecommendations(searchTerm) {
            const items = document.querySelectorAll('.thesis-feed-item');
            const recommendations = [];
            
            items.forEach(item => {
                const title = item.dataset.title;
                const author = item.dataset.author;
                const department = item.dataset.department;
                const keywords = item.dataset.keywords;
                
                // Check if matches search term
                if (title.includes(searchTerm) || author.includes(searchTerm) || department.includes(searchTerm) || keywords.includes(searchTerm)) {
                    const thesisElement = item.closest('.thesis-feed-item');
                    const titleElement = thesisElement.querySelector('h3');
                    const titleText = titleElement ? titleElement.textContent : '';
                    
                    recommendations.push({
                        title: titleText,
                        author: author,
                        department: department,
                        keywords: keywords
                    });
                }
            });

            displayRecommendations(recommendations, searchTerm);
        }

        function displayRecommendations(recommendations, searchTerm) {
            const container = document.getElementById('search-recommendations');
            
            if (recommendations.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-[#CCC5B9]">No matching Thesis found</div>';
                container.classList.remove('hidden');
                return;
            }

            let html = '<div class="p-2">';
            recommendations.slice(0, 5).forEach(rec => {
                const highlightedTitle = highlightText(rec.title, searchTerm);
                html += `
                    <div class="p-3 hover:bg-[#FFFCF2] rounded-lg cursor-pointer transition-colors" onclick="selectRecommendation('${rec.title.replace(/'/g, "\\'")}')">
                        <p class="text-sm font-medium text-[#252422]">${highlightedTitle}</p>
                        <p class="text-xs text-[#CCC5B9]">${rec.author} · ${rec.department}</p>
                    </div>
                `;
            });
            html += '</div>';
            
            container.innerHTML = html;
            container.classList.remove('hidden');
        }

        function highlightText(text, searchTerm) {
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<span class="bg-[#EB5E28]/20 text-[#EB5E28] font-semibold">$1</span>');
        }

        function selectRecommendation(title) {
            document.getElementById('search-input').value = title;
            document.getElementById('search-recommendations').classList.add('hidden');
            filterTheses();
        }


        function filterTheses() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const items = document.querySelectorAll('.thesis-feed-item');

            items.forEach(item => {
                const title = item.dataset.title;
                const author = item.dataset.author;
                const uploader = item.dataset.uploader;
                const keywords = item.dataset.keywords;

                const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm) || uploader.includes(searchTerm) || keywords.includes(searchTerm);

                if (matchesSearch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function toggleComments(thesisId) {
            const commentsSection = document.getElementById('comments-' + thesisId);
            commentsSection.classList.toggle('hidden');
        }

        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            replyForm.classList.toggle('hidden');
        }

        function toggleReplies(commentId) {
            const repliesSection = document.getElementById('replies-' + commentId);
            repliesSection.classList.toggle('hidden');
        }

        // Scroll to specific comment from notification link
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash && hash.includes('-comment-')) {
                const parts = hash.split('-comment-');
                if (parts.length === 2) {
                    const thesisId = parts[0].replace('#thesis-', '');
                    const commentId = parts[1];

                    // Open comments section for this thesis
                    const commentsSection = document.getElementById('comments-' + thesisId);
                    if (commentsSection) {
                        commentsSection.classList.remove('hidden');
                    }

                    // Scroll to specific comment
                    setTimeout(function() {
                        const commentElement = document.getElementById('comment-' + commentId);
                        if (commentElement) {
                            commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            // Highlight the comment briefly
                            commentElement.classList.add('bg-[#EB5E28]/10');
                            setTimeout(function() {
                                commentElement.classList.remove('bg-[#EB5E28]/10');
                            }, 2000);
                        }
                    }, 300);
                }
            }
        });
    </script>
</x-app-layout>
