<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Inbox</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Messages from users</p>
                </div>
                <div class="divide-y divide-[#CCC5B9]/20">
                    @forelse($messages as $message)
                        <a href="{{ route('admin.messages.show', $message->sender) }}" class="block hover:bg-[#FFFCF2] transition-colors">
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
</x-app-layout>
