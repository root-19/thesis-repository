<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-[#252422]">Inbox</h3>
                        <p class="text-sm text-[#CCC5B9] mt-1">All messages</p>
                    </div>
                    <a href="#" onclick="openNewMessageModal()" class="px-4 py-2 rounded-full bg-[#EB5E28] text-white text-sm font-medium hover:bg-[#d45220] transition-colors">
                        New Message
                    </a>
                </div>
                <div class="divide-y divide-[#CCC5B9]/20">
                    @forelse($messages as $message)
                        <a href="{{ route('author.messages.show', $message->sender) }}" class="block hover:bg-[#FFFCF2] transition-colors">
                            <div class="p-6 flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#FFFCF2] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg font-bold text-[#EB5E28]">{{ strtoupper(substr($message->sender->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-semibold text-[#252422]">{{ $message->sender->name }}</h4>
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
            <form method="POST" action="{{ route('author.messages.new') }}">
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
    </script>
</x-app-layout>
