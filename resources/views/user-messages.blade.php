<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                @if (isset($author))
                    <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center gap-4">
                        <a href="{{ route('user.messages') }}" class="text-[#CCC5B9] hover:text-[#EB5E28] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        @if ($author->profile_image_path)
                            <img src="{{ asset('storage/' . $author->profile_image_path) }}" alt="{{ $author->name }}" class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="h-12 w-12 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                <span class="text-lg font-semibold text-[#403D39]">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold text-[#252422]">{{ $author->name }}</h3>
                            <p class="text-sm text-[#CCC5B9]">{{ $author->email }}</p>
                        </div>
                    </div>

                    <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4 bg-[#FFFCF2]">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md {{ $message->sender_id === auth()->id() ? 'bg-[#EB5E28] text-white' : 'bg-white text-[#252422] border border-[#CCC5B9]/20' }} rounded-2xl px-4 py-3 shadow-sm">
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-white/70' : 'text-[#CCC5B9]' }} mt-1">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-[#CCC5B9]">No messages yet. Start a conversation with {{ $author->name }}.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-6 border-t border-[#CCC5B9]/20">
                        <form method="POST" action="{{ route('user.messages.send', $author) }}">
                            @csrf
                            <input type="hidden" name="author_id" value="{{ $author->id }}">
                            <div class="flex gap-4">
                                <input type="text" name="message" placeholder="Type your message..." required
                                    class="flex-1 rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]" />
                                <button type="submit" class="px-6 py-3 bg-[#EB5E28] text-white rounded-xl text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-6 border-b border-[#CCC5B9]/20">
                        <h3 class="text-lg font-semibold text-[#252422]">Message Authors</h3>
                        <p class="text-sm text-[#CCC5B9] mt-1">Select an author to start a conversation</p>
                    </div>
                    <div class="divide-y divide-[#CCC5B9]/20">
                        @forelse($authors as $author)
                            <a href="{{ route('user.messages.show', $author) }}" class="block hover:bg-[#FFFCF2] transition-colors">
                                <div class="p-6 flex items-center gap-4">
                                    @if ($author->profile_image_path)
                                        <img src="{{ asset('storage/' . $author->profile_image_path) }}" alt="{{ $author->name }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                            <span class="text-lg font-semibold text-[#403D39]">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-[#252422]">{{ $author->name }}</h4>
                                        <p class="text-xs text-[#CCC5B9]">{{ $author->email }}</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#CCC5B9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <p class="text-[#CCC5B9]">No authors found. Please contact support.</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom on page load with delay
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        });

        // Listen for real-time messages
        @if (auth()->check())
            Echo.private(`messages.{{ auth()->id() }}`)
                .listen('.MessageSent', (e) => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        const messageHtml = `
                            <div class="flex ${e.message.sender_id === {{ auth()->id() } ? 'justify-end' : 'justify-start'}">
                                <div class="max-w-xs lg:max-w-md ${e.message.sender_id === {{ auth()->id() } ? 'bg-[#EB5E28] text-white' : 'bg-white text-[#252422] border border-[#CCC5B9]/20'} rounded-2xl px-4 py-3 shadow-sm">
                                    <p class="text-sm">${e.message.message}</p>
                                    <p class="text-xs ${e.message.sender_id === {{ auth()->id() } ? 'text-white/70' : 'text-[#CCC5B9]'} mt-1">${e.message.created_at}</p>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', messageHtml);
                        container.scrollTop = container.scrollHeight;
                    }
                });
        @endif
    </script>
</x-app-layout>
