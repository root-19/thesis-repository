<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('author.inbox') }}" class="text-[#CCC5B9] hover:text-[#252422]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h3 class="text-lg font-semibold text-[#252422]">New Message</h3>
                            <p class="text-sm text-[#CCC5B9] mt-1">Send a message to a user</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="/author/messages/new">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-[#252422] mb-2">Select User</label>
                                <select name="user_id" required class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50">
                                    <option value="">Choose a user...</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#252422] mb-2">Message</label>
                                <textarea name="message" rows="6" required class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]" placeholder="Type your message..."></textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('author.inbox') }}" class="px-4 py-2 rounded-lg border border-[#CCC5B9]/40 text-[#252422] hover:bg-[#FFFCF2] transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-[#EB5E28] text-white hover:bg-[#d45220] transition-colors">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
