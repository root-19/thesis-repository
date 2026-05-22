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
                                        <a href="{{ auth()->user()->isAdmin() ? route('admin.feed') : (auth()->user()->isAuthor() ? route('author.feed') : route('dashboard')) }}#thesis-{{ $notification->data['thesis_id'] }}{{ isset($notification->data['comment_id']) ? '-comment-' . $notification->data['comment_id'] : '' }}" target="_blank" class="p-2 rounded-lg bg-[#FFFCF2] text-[#403D39] hover:bg-[#EB5E28] hover:text-white transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
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
</x-app-layout>
