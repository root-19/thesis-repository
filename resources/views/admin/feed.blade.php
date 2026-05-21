<x-app-layout>

    <div class="min-h-screen bg-[#FFFCF2]">
        <!-- Hero Section -->
        <div class="bg-gradient-to-b from-white to-[#FFFCF2] py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-[#252422] mb-4">
                        Interactive Research<br>
                        <span class="text-[#EB5E28]">Starts Here.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-[#403D39] leading-relaxed max-w-3xl mx-auto mb-8">
                        Access thousands of thesis papers, capstone projects, and scholarly articles.
                        Interact with researchers and download thesis papers for your academic needs.
                    </p>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <div class="flex items-center gap-2 text-[#403D39]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-semibold">{{ $stats['total'] }} Thesis Papers</span>
                        </div>
                        <div class="flex items-center gap-2 text-[#403D39]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="font-semibold">{{ $stats['years'] }} Years of Research</span>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="max-w-2xl mx-auto">
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Search thesis by title, author, or keyword..."
                                class="w-full px-6 py-4 rounded-2xl border-2 border-[#CCC5B9]/40 bg-white text-[#252422] placeholder-[#CCC5B9] focus:border-[#EB5E28] focus:ring-4 focus:ring-[#EB5E28]/10 transition-all text-lg shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-6 top-1/2 transform -translate-y-1/2 h-6 w-6 text-[#CCC5B9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <!-- Search Recommendations -->
                            <div id="search-recommendations" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-lg border border-[#CCC5B9]/20 hidden z-50 max-h-96 overflow-y-auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Papers Section -->
        <section id="research" class="py-24 bg-white">
            <div class="w-full max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
                    <div>
                        <span class="text-sm font-semibold text-[#EB5E28] uppercase tracking-widest">Latest Research</span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-[#252422] mt-3">Featured Papers</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="resetFilters" class="px-5 py-2.5 rounded-full bg-[#252422] text-white text-sm font-semibold hover:bg-[#403D39] transition-colors">Reset filters</button>
                    </div>
                </div>

                @if ($theses->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="authorGrid">
                        @foreach ($theses as $thesis)
                            @php
                                $author = $thesis->author ?? 'Unknown';
                                $department = $thesis->department ?? 'General';
                                $year = $thesis->thesis_date->format('Y');
                                $title = $thesis->title;
                            @endphp
                            <article class="group bg-white rounded-3xl p-7 border border-[#CCC5B9]/20 hover:border-[#EB5E28]/20 hover:shadow-2xl hover:shadow-[#EB5E28]/5 transition-all duration-300" data-author-card data-filter-text="{{ strtolower(collect([$author, $title, $department])->filter()->implode(' ')) }}">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 rounded-full bg-[#EB5E28]/10 text-[#EB5E28] text-xs font-semibold">{{ $department }}</span>
                                    <span class="text-sm text-[#CCC5B9] font-medium">{{ $year }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#252422] mb-3 line-clamp-2 group-hover:text-[#EB5E28] transition-colors">{{ $title }}</h3>
                                <p class="text-sm text-[#403D39] mb-4">By <span class="font-semibold">{{ $author }}</span></p>
                                <div class="flex items-center gap-3">
                                    <a href="{{ asset('storage/' . $thesis->pdf_file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-[#EB5E28] hover:gap-3 transition-all">
                                        View PDF
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </a>
                                    <a href="{{ route('admin.theses.edit', $thesis) }}" class="text-sm font-semibold text-[#403D39] hover:text-[#EB5E28] transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-[#CCC5B9]">No thesis papers found. Upload some thesis papers to get started.</p>
                    </div>
                @endif

                <div class="mt-12 bg-white rounded-3xl p-10 text-center text-[#403D39] border border-dashed border-[#CCC5B9]/30 hidden" id="emptyState">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-[#CCC5B9]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-lg font-medium">No research papers match your search.</p>
                    <p class="text-sm mt-1">Try another keyword or reset the filters to browse all papers.</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        let searchTimeout;
        let searchRecommendationsTimeout;

        document.getElementById('search-input').addEventListener('input', function(e) {
            filterTheses();
            
            // Show search recommendations
            clearTimeout(searchRecommendationsTimeout);
            const searchTerm = e.target.value.trim();
            
            if (searchTerm.length >= 2) {
                searchRecommendationsTimeout = setTimeout(() => {
                    fetchSearchRecommendations(searchTerm);
                }, 300);
            } else {
                document.getElementById('search-recommendations').classList.add('hidden');
            }
        });

        // Hide recommendations when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#search-input') && !e.target.closest('#search-recommendations')) {
                document.getElementById('search-recommendations').classList.add('hidden');
            }
        });

        function fetchSearchRecommendations(searchTerm) {
            const items = document.querySelectorAll('[data-author-card]');
            const recommendations = [];
            
            items.forEach(item => {
                const filterText = item.dataset.filterText || '';
                
                // Check if matches search term
                if (filterText.includes(searchTerm.toLowerCase())) {
                    const titleElement = item.querySelector('h3');
                    const titleText = titleElement ? titleElement.textContent : '';
                    
                    recommendations.push({
                        title: titleText
                    });
                }
            });

            displayRecommendations(recommendations, searchTerm);
        }

        function displayRecommendations(recommendations, searchTerm) {
            const container = document.getElementById('search-recommendations');
            
            if (recommendations.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-[#CCC5B9]">No matching Thesis found</div>';
                container.classList.remove('hidden');
                return;
            }

            let html = '<div class="p-2">';
            recommendations.slice(0, 5).forEach(rec => {
                const highlightedTitle = highlightText(rec.title, searchTerm);
                html += `
                    <div class="p-3 hover:bg-[#FFFCF2] rounded-lg cursor-pointer transition-colors" onclick="selectRecommendation('${rec.title.replace(/'/g, "\\'")}')">
                        <p class="text-sm font-medium text-[#252422]">${highlightedTitle}</p>
                    </div>
                `;
            });
            html += '</div>';
            
            container.innerHTML = html;
            container.classList.remove('hidden');
        }

        function highlightText(text, searchTerm) {
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<span class="bg-[#EB5E28]/20 text-[#EB5E28] font-semibold">$1</span>');
        }

        function selectRecommendation(title) {
            document.getElementById('search-input').value = title;
            document.getElementById('search-recommendations').classList.add('hidden');
            filterTheses();
        }

        function filterTheses() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const items = document.querySelectorAll('[data-author-card]');

            items.forEach(item => {
                const filterText = item.dataset.filterText || '';
                
                if (filterText.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide empty state
            const visibleItems = Array.from(items).filter(item => item.style.display !== 'none');
            const emptyState = document.getElementById('emptyState');
            if (visibleItems.length === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        document.getElementById('resetFilters')?.addEventListener('click', () => {
            document.getElementById('search-input').value = '';
            filterTheses();
        });
    </script>
</x-app-layout>
