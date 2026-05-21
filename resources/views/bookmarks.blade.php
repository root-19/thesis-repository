<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Bookmarked Studies</h2>
                    <p class="text-[#CCC5B9] mt-1">Your saved research papers</p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if ($bookmarks->count() > 0)
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($bookmarks as $bookmark)
                                <div class="bg-[#FFFCF2] rounded-2xl p-6 border border-[#CCC5B9]/20 hover:shadow-lg transition-shadow">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-[#252422] mb-2 line-clamp-2">{{ $bookmark->thesis->title }}</h3>
                                            <p class="text-sm text-[#CCC5B9] mb-2">{{ $bookmark->thesis->thesis_date->format('M d, Y') }}</p>
                                            <p class="text-sm text-[#403D39] line-clamp-3 mb-3">{{ $bookmark->thesis->description }}</p>
                                            
                                            <div class="flex items-center gap-2 text-sm text-[#CCC5B9]">
                                                <span>Author:</span>
                                                <span class="text-[#252422] font-medium">{{ $bookmark->thesis->author }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 pt-4 border-t border-[#CCC5B9]/20">
                                        <a href="#thesis-{{ $bookmark->thesis->id }}" class="flex-1 text-center px-4 py-2 rounded-lg bg-[#252422] text-white text-sm font-medium hover:bg-[#403D39] transition-colors">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('bookmarks.destroy', $bookmark->thesis) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 rounded-lg border border-red-300 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#CCC5B9] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <p class="text-[#CCC5B9]">No bookmarked studies yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
