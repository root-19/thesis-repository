<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Add Co-Author</h2>
                    <p class="text-[#CCC5B9] mt-1">Add co-authors to thesis: {{ $thesis->title }}</p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-50 rounded-xl border border-red-200">
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-[#252422] mb-2">Search Users</label>
                        <input type="text" id="search" name="search" 
                            class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                            placeholder="Type to search users..." 
                            onkeyup="searchUsers(this.value)">
                    </div>

                    <div id="search-results" class="space-y-3 mb-6">
                        <p class="text-sm text-[#CCC5B9]">Start typing to search users...</p>
                    </div>

                    <div class="border-t border-[#CCC5B9]/20 pt-6">
                        <h3 class="text-lg font-semibold text-[#252422] mb-4">Current Co-Authors</h3>
                        @if ($thesis->coAuthors->count() > 0)
                            <div class="space-y-3">
                                @foreach ($thesis->coAuthors as $coAuthor)
                                    <div class="flex items-center justify-between bg-[#FFFCF2] rounded-xl p-4">
                                        <div class="flex items-center gap-3">
                                            @if ($coAuthor->profile_image_path)
                                                <img src="{{ asset('storage/' . $coAuthor->profile_image_path) }}" alt="{{ $coAuthor->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                                    <span class="text-sm font-semibold text-[#403D39]">{{ strtoupper(substr($coAuthor->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-[#252422]">{{ $coAuthor->name }}</p>
                                                <p class="text-xs text-[#CCC5B9]">{{ $coAuthor->email }}</p>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('thesis.co-author.destroy', [$thesis, $coAuthor]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-[#CCC5B9] text-white rounded-lg text-xs font-medium hover:bg-[#b5b4b0] transition-colors">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-[#CCC5B9]">No co-authors added yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;

        function searchUsers(query) {
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                document.getElementById('search-results').innerHTML = '<p class="text-sm text-[#CCC5B9]">Start typing to search users...</p>';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/theses/{{ $thesis->id }}/co-authors/search?search=${encodeURIComponent(query)}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('search-results').innerHTML = html;
                    });
            }, 300);
        }
    </script>
</x-app-layout>
