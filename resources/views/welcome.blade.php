<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Arcoe - Discover, search, and explore academic research papers, thesis documents, and scholarly articles from students and researchers.">
    <title>Arcoe - Academic Research Repository</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --ivory: #FFFCF2;
            --warm-taupe: #CCC5B9;
            --slate-charcoal: #403D39;
            --true-black: #252422;
            --burnt-orange: #EB5E28;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--ivory);
            color: var(--true-black);
        }
        .animate-fade-up {
            animation: fadeUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(24px);
        }
        .animate-fade-up-delay-1 { animation-delay: 0.1s; }
        .animate-fade-up-delay-2 { animation-delay: 0.2s; }
        .animate-fade-up-delay-3 { animation-delay: 0.3s; }
        .animate-fade-up-delay-4 { animation-delay: 0.4s; }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .glass-card {
            background: rgba(255, 252, 242, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(64, 61, 57, 0.08);
        }
        .hero-gradient {
            background:
                radial-gradient(circle at 20% 20%, rgba(235, 94, 40, 0.08), transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(64, 61, 57, 0.05), transparent 50%),
                linear-gradient(180deg, #FFFCF2 0%, #FFF8E8 100%);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    @php
        $thesisCollection = $theses ?? collect();
        $stats = $stats ?? ['total' => 0, 'years' => 0, 'downloads' => 0];
    @endphp

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-card border-b border-[#403D39]/10">
        <div class="w-full max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center font-bold text-xl text-[#252422]">
                <img src="/logo.png" alt="Arcoe" class="h-25 w-40">
                {{-- <span>Arcoe</span> --}}
            </a>
            <div class="flex items-center gap-6">
                <a href="#features" class="hidden md:block text-sm font-medium text-[#403D39] hover:text-[#EB5E28] transition-colors">Features</a>
                <a href="#research" class="hidden md:block text-sm font-medium text-[#403D39] hover:text-[#EB5E28] transition-colors">Research</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-[#252422] text-white text-sm font-semibold hover:bg-[#403D39] transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-[#403D39] hover:text-[#EB5E28] transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-[#EB5E28] text-white text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">Create account</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 md:pt-44 md:pb-28">
        <div class="w-full max-w-7xl mx-auto px-6">
            <div class="max-w-4xl">
                <h1 class="text-5xl md:text-7xl font-extrabold leading-[1.1] text-[#252422] mb-6 animate-fade-up">
                    Interactive Research<br>
                    <span class="text-[#EB5E28]">Starts Here.</span>
                </h1>
                <p class="text-lg md:text-xl text-[#403D39] leading-relaxed max-w-2xl mb-10 animate-fade-up animate-fade-up-delay-2">
                    Access and Retrieve undergraduate thesis papers authored by Computer Engineering students at the Polytechnic University of the Philippines.<br><br>
                    Interact with Thesis Researchers.<br>
                    Search by title, author, or keyword to find the thesis you need.
                </p>

                <div class="relative max-w-2xl animate-fade-up animate-fade-up-delay-3">
                    <form class="flex items-center gap-3 bg-white rounded-2xl p-2 shadow-xl shadow-[#252422]/10 border border-[#CCC5B9]/30" id="authorSearchForm" autocomplete="off">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#CCC5B9] ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input id="authorSearchInput" type="search" placeholder="Search thesis titles, authors, or keywords..." aria-label="Search research papers" class="flex-1 border-none bg-transparent px-2 py-3 text-base text-[#252422] focus:outline-none placeholder:text-[#CCC5B9]">
                        <button type="submit" class="px-7 py-3.5 rounded-xl bg-[#252422] text-white font-semibold hover:bg-[#403D39] transition-all duration-300 shadow-lg">Search</button>
                    </form>
                    <!-- Search Recommendations -->
                    <div id="search-recommendations" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-lg border border-[#CCC5B9]/20 hidden z-50 max-h-96 overflow-y-auto">
                    </div>
                </div>

             
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 border-y border-[#CCC5B9]/20">
        <div class="w-full max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-extrabold text-[#EB5E28] mb-1">{{ $stats['total'] }}</div>
                    <div class="text-sm text-[#403D39] font-medium uppercase tracking-wider">Thesis Papers</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-extrabold text-[#252422] mb-1">{{ $stats['total'] }}</div>
                    <div class="text-sm text-[#403D39] font-medium uppercase tracking-wider">Researchers</div>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center animate-fade-up animate-fade-up-delay-3">
                    <div class="text-4xl md:text-5xl font-extrabold text-[#252422] mb-1">{{ $stats['years'] }}</div>
                    <div class="text-sm text-[#403D39] font-medium uppercase tracking-wider">Years of Research</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24">
        <div class="w-full max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#EB5E28] uppercase tracking-widest">Why ArCOE</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-[#252422] mt-3 mb-4">It is Where Research Becomes Interactive</h2>
                <p class="text-lg text-[#403D39]">An interactive platform where users can access, retrieve, and talk to researchers about specific thesis.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="group p-8 rounded-3xl bg-white border border-[#CCC5B9]/20 hover:border-[#EB5E28]/30 hover:shadow-xl hover:shadow-[#EB5E28]/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-[#EB5E28]/10 flex items-center justify-center mb-6 group-hover:bg-[#EB5E28] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#252422] mb-2">Advanced Search</h3>
                    <p class="text-[#403D39] leading-relaxed">Find research papers instantly with intelligent search across titles, authors, and keywords.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-[#CCC5B9]/20 hover:border-[#EB5E28]/30 hover:shadow-xl hover:shadow-[#EB5E28]/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-[#EB5E28]/10 flex items-center justify-center mb-6 group-hover:bg-[#EB5E28] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#252422] mb-2">Smart Filtering</h3>
                    <p class="text-[#403D39] leading-relaxed">Narrow results by publication year, research topic, or adviser to find exactly what you need.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-[#CCC5B9]/20 hover:border-[#EB5E28]/30 hover:shadow-xl hover:shadow-[#EB5E28]/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-[#EB5E28]/10 flex items-center justify-center mb-6 group-hover:bg-[#EB5E28] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#252422] mb-2">PDF Preview</h3>
                    <p class="text-[#403D39] leading-relaxed">View and read thesis documents directly in your browser.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-[#CCC5B9]/20 hover:border-[#EB5E28]/30 hover:shadow-xl hover:shadow-[#EB5E28]/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-[#EB5E28]/10 flex items-center justify-center mb-6 group-hover:bg-[#EB5E28] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#252422] mb-2">Download/Upload</h3>
                    <p class="text-[#403D39] leading-relaxed">Download thesis studies and upload thesis papers as a Researcher.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-[#CCC5B9]/20 hover:border-[#EB5E28]/30 hover:shadow-xl hover:shadow-[#EB5E28]/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-[#EB5E28]/10 flex items-center justify-center mb-6 group-hover:bg-[#EB5E28] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#252422] mb-2">Bookmark Papers</h3>
                    <p class="text-[#403D39] leading-relaxed">Save research papers for later reading. Build your personal collection of important academic works.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-white border border-[#CCC5B9]/20 hover:border-[#EB5E28]/30 hover:shadow-xl hover:shadow-[#EB5E28]/5 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-[#EB5E28]/10 flex items-center justify-center mb-6 group-hover:bg-[#EB5E28] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#252422] mb-2">Interactive Reading</h3>
                    <p class="text-[#403D39] leading-relaxed">Enjoy interacting with researchers with the personal message and public comment and reply feature.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Research Section -->
    <section id="research" class="py-24">
        <div class="w-full max-w-7xl mx-auto px-6">
            <div class="flex flex-wrap justify-between items-end gap-6 mb-12">
                <div>
                    <span class="text-sm font-semibold text-[#EB5E28] uppercase tracking-widest">Latest Research</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-[#252422] mt-3">Featured Papers</h2>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="resetFilters" class="px-5 py-2.5 rounded-full bg-[#252422] text-white text-sm font-semibold hover:bg-[#403D39] transition-colors">Reset filters</button>
                </div>
            </div>

            @if ($thesisCollection->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="authorGrid">
                    @foreach ($thesisCollection->take(6) as $thesis)
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
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#EB5E28] hover:gap-3 transition-all">
                                View Research
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
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

    <!-- How It Works Section -->
    <section class="py-24 bg-[#FFFCF2] border-y border-[#CCC5B9]/20">
        <div class="w-full max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#EB5E28] uppercase tracking-widest">Simple Process</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-[#252422] mt-3 mb-4">How ArCOE Works</h2>
                <p class="text-lg text-[#403D39]">Find the thesis you need.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8">
                    <div class="w-16 h-16 rounded-2xl bg-[#EB5E28] text-white flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg shadow-[#EB5E28]/20">1</div>
                    <h3 class="text-xl font-bold text-[#252422] mb-3">Search Research</h3>
                    <p class="text-[#403D39] leading-relaxed">Search by title, author, or keyword to find the thesis you need.</p>
                </div>
                <div class="text-center p-8">
                    <div class="w-16 h-16 rounded-2xl bg-[#252422] text-white flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg">2</div>
                    <h3 class="text-xl font-bold text-[#252422] mb-3">Read & Learn</h3>
                    <p class="text-[#403D39] leading-relaxed">Access full papers and collaborate with other users and researchers of topics you preferred.</p>
                </div>
                <div class="text-center p-8">
                    <div class="w-16 h-16 rounded-2xl bg-[#EB5E28] text-white flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg shadow-[#EB5E28]/20">3</div>
                    <h3 class="text-xl font-bold text-[#252422] mb-3">Save</h3>
                    <p class="text-[#403D39] leading-relaxed">Download thesis papers for your academic needs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-[#252422]">
        <div class="w-full max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-5xl font-extrabold text-[#FFFCF2] mb-6">Ready to Explore Academic Research?</h2>
            <p class="text-lg text-[#CCC5B9] mb-10 max-w-2xl mx-auto">Start discovering thesis papers, capstone projects, and scholarly articles from students and researchers.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#research" class="px-8 py-4 rounded-full bg-[#EB5E28] text-white font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">Browse Research Papers</a>
                <a href="#features" class="px-8 py-4 rounded-full bg-transparent border border-[#CCC5B9]/30 text-[#FFFCF2] font-semibold hover:bg-[#FFFCF2]/10 transition-colors">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 bg-[#FFFCF2] border-t border-[#CCC5B9]/20">
        <div class="w-full max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 gap-12 mb-12">
                <div class="text-center">
                    <a href="/" class="flex items-center justify-center font-bold text-xl text-[#252422]">
                        <img src="/logo.png" alt="Arcoe" class="h-20 w-20">
                    </a>
                    <p class="text-sm text-[#403D39] leading-relaxed mt-6">A WEB-BASED CENTRALIZED THESIS REPOSITORY SYSTEM FOR ENHANCED ACADEMIC INFORMATION RETRIEVAL AND USER INTERACTION</p>
                </div>
            </div>
            <div class="pt-8 border-t border-[#CCC5B9]/20 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#CCC5B9]">&copy; {{ date('Y') }} Arcoe. All rights reserved.</p>
                <p class="text-sm text-[#CCC5B9]">Built for academic excellence and research discovery.</p>
            </div>
        </div>
    </footer>

    <script>
        let searchTimeout;
        let searchRecommendationsTimeout;

        const searchForm = document.getElementById('authorSearchForm');
        const searchInput = document.getElementById('authorSearchInput');
        const resetFilters = document.getElementById('resetFilters');
        const authorCards = Array.from(document.querySelectorAll('[data-author-card]'));
        const emptyState = document.getElementById('emptyState');
        const searchRecommendations = document.getElementById('search-recommendations');

        const filterAuthors = (query) => {
            const normalizedQuery = query.trim().toLowerCase();
            let visible = 0;

            authorCards.forEach(card => {
                const filterText = card.dataset.filterText || '';
                const matches = !normalizedQuery || filterText.includes(normalizedQuery);
                card.style.display = matches ? '' : 'none';
                if (matches) visible += 1;
            });

            emptyState.style.display = visible === 0 ? 'block' : 'none';
        };

        searchInput?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();
            
            // Filter authors in real-time
            filterAuthors(searchTerm);
            
            // Show search recommendations
            clearTimeout(searchRecommendationsTimeout);
            
            if (searchTerm.length >= 2) {
                searchRecommendationsTimeout = setTimeout(() => {
                    fetchSearchRecommendations(searchTerm);
                    // Auto-scroll to research section when keywords are entered
                    document.getElementById('research')?.scrollIntoView({ behavior: 'smooth' });
                }, 300);
            } else {
                searchRecommendations.classList.add('hidden');
            }
        });

        // Hide recommendations when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#authorSearchForm') && !e.target.closest('#search-recommendations')) {
                searchRecommendations.classList.add('hidden');
            }
        });

        function fetchSearchRecommendations(searchTerm) {
            const items = document.querySelectorAll('[data-author-card]');
            const recommendations = [];
            
            items.forEach(item => {
                const filterText = item.dataset.filterText || '';
                const titleElement = item.querySelector('h3');
                const titleText = titleElement ? titleElement.textContent : '';
                const authorElement = item.querySelector('p span');
                const authorText = authorElement ? authorElement.textContent : '';
                
                // Check if matches search term
                if (filterText.includes(searchTerm.toLowerCase())) {
                    recommendations.push({
                        title: titleText,
                        author: authorText
                    });
                }
            });

            displayRecommendations(recommendations, searchTerm);
        }

        function displayRecommendations(recommendations, searchTerm) {
            if (recommendations.length === 0) {
                searchRecommendations.innerHTML = '<div class="p-4 text-center text-[#CCC5B9]">No matching Thesis found</div>';
                searchRecommendations.classList.remove('hidden');
                return;
            }

            let html = '<div class="p-2">';
            recommendations.slice(0, 5).forEach(rec => {
                const highlightedTitle = highlightText(rec.title, searchTerm);
                html += `
                    <div class="p-3 hover:bg-[#FFFCF2] rounded-lg cursor-pointer transition-colors" onclick="selectRecommendation('${rec.title.replace(/'/g, "\\'")}')">
                        <p class="text-sm font-medium text-[#252422]">${highlightedTitle}</p>
                        <p class="text-xs text-[#CCC5B9]">${rec.author}</p>
                    </div>
                `;
            });
            html += '</div>';
            
            searchRecommendations.innerHTML = html;
            searchRecommendations.classList.remove('hidden');
        }

        function highlightText(text, searchTerm) {
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<span class="bg-[#EB5E28]/20 text-[#EB5E28] font-semibold">$1</span>');
        }

        function selectRecommendation(title) {
            searchInput.value = title;
            searchRecommendations.classList.add('hidden');
            filterAuthors(title);
            document.getElementById('research')?.scrollIntoView({ behavior: 'smooth' });
        }

        searchForm?.addEventListener('submit', (event) => {
            event.preventDefault();
            const searchTerm = searchInput?.value?.trim() || '';
            filterAuthors(searchTerm);
            
            // Only scroll if there are matching results
            if (searchTerm.length > 0) {
                const visibleCards = Array.from(document.querySelectorAll('[data-author-card]')).filter(card => card.style.display !== 'none');
                if (visibleCards.length > 0) {
                    document.getElementById('research')?.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });

        resetFilters?.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = '';
            }
            filterAuthors('');
            searchRecommendations.classList.add('hidden');
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>