<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Works</h2>
                    <p class="text-[#CCC5B9] mt-1">View and manage your research papers</p>
                </div>

                <div class="p-6">
                    @if ($theses->count() > 0)
                        <div class="space-y-6">
                            @foreach ($theses as $thesis)
                                <div class="bg-[#FFFCF2] rounded-2xl p-6 border border-[#CCC5B9]/20">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <span class="px-3 py-1 bg-[#EB5E28]/10 text-[#EB5E28] rounded-full text-xs font-medium">
                                                    {{ $thesis->thesis_date->format('M d, Y') }}
                                                </span>
                                                @if ($thesis->user_id === auth()->id())
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                        Primary Author
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                        Co-Author
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <h3 class="text-xl font-bold text-[#252422] mb-2">{{ $thesis->title }}</h3>
                                            <p class="text-sm text-[#403D39] mb-3 line-clamp-2">{{ $thesis->description }}</p>
                                            
                                            @if ($thesis->keywords)
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    @foreach (explode(',', $thesis->keywords) as $keyword)
                                                        <span class="px-2 py-1 bg-[#CCC5B9]/20 text-[#403D39] rounded-lg text-xs">
                                                            {{ trim($keyword) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center gap-2 text-sm text-[#CCC5B9]">
                                                <span>Researchers:</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[#252422] font-medium">{{ $thesis->author }}</span>
                                                    @if ($thesis->coAuthors->count() > 0)
                                                        @foreach ($thesis->coAuthors as $coAuthor)
                                                            <span>, {{ $coAuthor->name }}</span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-row lg:flex-col gap-2">
                                            <a href="{{ asset('storage/' . $thesis->pdf_file_path) }}" target="_blank" class="px-4 py-2 rounded-lg bg-[#252422] text-white text-sm font-medium hover:bg-[#403D39] transition-colors">
                                                View PDF
                                            </a>
                                            <a href="{{ route('author.works.edit', $thesis) }}" class="px-4 py-2 rounded-lg bg-[#EB5E28] text-white text-sm font-medium hover:bg-[#d45220] transition-colors">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('author.works.destroy', $thesis) }}" onsubmit="return confirm('Are you sure you want to request deletion of this thesis?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 rounded-lg border border-red-300 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#CCC5B9] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-[#CCC5B9]">No works yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
