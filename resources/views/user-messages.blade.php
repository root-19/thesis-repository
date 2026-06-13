<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                @if (isset($user))
                    <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center gap-4">
                        <a href="{{ route('user.messages') }}" class="text-[#CCC5B9] hover:text-[#EB5E28] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        @if ($user->profile_image_path)
                            <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="h-12 w-12 rounded-full bg-[#FFFCF2] flex items-center justify-center">
                                <span class="text-lg font-semibold text-[#403D39]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold text-[#252422]">{{ $user->name }}</h3>
                            <p class="text-sm text-[#CCC5B9]">{{ $user->email }}</p>
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
                                <p class="text-[#CCC5B9]">No messages yet. Start a conversation with {{ $user->name }}.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-6 border-t border-[#CCC5B9]/20">
                        <form method="POST" action="{{ route('user.messages.send', $user) }}">
                            @csrf
                            <input type="hidden" name="author_id" value="{{ $user->id }}">
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
                    <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-[#252422]">Messages</h3>
                            <p class="text-sm text-[#CCC5B9] mt-1">All your conversations</p>
                        </div>
                        <a href="#" onclick="openNewMessageModal()" class="px-4 py-2 rounded-full bg-[#EB5E28] text-white text-sm font-medium hover:bg-[#d45220] transition-colors">
                            New Message
                        </a>
                    </div>
                    <div class="divide-y divide-[#CCC5B9]/20">
                        @forelse($messages as $message)
                            @php
                                $otherUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                            @endphp
                            <a href="{{ route('user.messages.show', $otherUser) }}" class="block hover:bg-[#FFFCF2] transition-colors">
                                <div class="p-6 flex items-start gap-4">
                                    @if ($otherUser->profile_image_path)
                                        <img src="{{ asset('storage/' . $otherUser->profile_image_path) }}" alt="{{ $otherUser->name }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 bg-[#FFFCF2] rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-lg font-bold text-[#EB5E28]">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-semibold text-[#252422]">{{ $otherUser->name }}</h4>
                                            <span class="text-xs text-[#CCC5B9]">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-[#403D39] truncate">{{ $message->message }}</p>
                                    </div>
                                    @if (! $message->is_read && $message->receiver_id === auth()->id())
                                        <div class="w-3 h-3 bg-[#EB5E28] rounded-full flex-shrink-0"></div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <div class="text-[#CCC5B9]">No messages yet</div>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- New Message Modal -->
    <div id="newMessageModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#252422]">New Message</h3>
                <button onclick="closeNewMessageModal()" class="text-[#CCC5B9] hover:text-[#252422]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('user.messages.new') }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#252422] mb-2">Select User</label>
                        <select name="user_id" required class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50">
                            <option value="">Choose a user...</option>
                            @php
                                $users = \App\Models\User::where('id', '!=', auth()->id())->get();
                            @endphp
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#252422] mb-2">Message</label>
                        <textarea name="message" rows="4" required class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]" placeholder="Type your message..."></textarea>
                    </div>
                </div>
                <div class="p-6 border-t border-[#CCC5B9]/20 flex justify-end gap-3">
                    <button type="button" onclick="closeNewMessageModal()" class="px-4 py-2 rounded-lg border border-[#CCC5B9]/40 text-[#252422] hover:bg-[#FFFCF2] transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#EB5E28] text-white hover:bg-[#d45220] transition-colors">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNewMessageModal() {
            document.getElementById('newMessageModal').classList.remove('hidden');
            document.getElementById('newMessageModal').classList.add('flex');
        }

        function closeNewMessageModal() {
            document.getElementById('newMessageModal').classList.add('hidden');
            document.getElementById('newMessageModal').classList.remove('flex');
        }

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
