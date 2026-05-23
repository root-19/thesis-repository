<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Search Thesis Papers</h2>
                    <p class="text-[#CCC5B9] mt-1">Find existing thesis papers to request co-researchership</p>
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

                    <form method="POST" action="{{ route('co-researcher-application.search.results') }}" class="mb-6">
                        @csrf
                        <div class="flex gap-4">
                            <input type="text" name="query" value="{{ $query ?? '' }}" placeholder="Search by title, description, or keywords..."
                                class="flex-1 rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]" />
                            <button type="submit" class="px-6 py-3 bg-[#EB5E28] text-white rounded-xl text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                                Search
                            </button>
                        </div>
                    </form>

                    @if (isset($theses) && $theses->count() > 0)
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($theses as $thesis)
                                <div class="bg-[#FFFCF2] rounded-2xl p-6 border border-[#CCC5B9]/20 hover:shadow-lg transition-shadow">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-[#252422] mb-2 line-clamp-2">{{ $thesis->title }}</h3>
                                            <p class="text-sm text-[#CCC5B9] mb-2">{{ $thesis->thesis_date->format('M d, Y') }}</p>
                                            <p class="text-sm text-[#403D39] line-clamp-3 mb-3">{{ $thesis->description }}</p>
                                            
                                            <div class="flex items-center gap-2 text-sm text-[#CCC5B9]">
                                                <span>Author:</span>
                                                <span class="text-[#252422] font-medium">{{ $thesis->author }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('co-researcher-application.request', $thesis) }}">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to request to become a co-researcher for this thesis?')" class="w-full px-4 py-2 rounded-lg bg-[#EB5E28] text-white text-sm font-medium hover:bg-[#d45220] transition-colors">
                                            Request Co-Researchership
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @elseif (isset($query))
                        <div class="text-center py-12">
                            <p class="text-[#CCC5B9]">No thesis papers found matching "{{ $query }}"</p>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#CCC5B9] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="text-[#CCC5B9]">Enter a search term to find thesis papers</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
