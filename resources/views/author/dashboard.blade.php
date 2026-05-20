<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Author Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Total Thesis</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\Thesis::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">My Thesis</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ \App\Models\Thesis::where('user_id', auth()->id())->orWhereHas('coAuthors', function($query) { $query->where('users.id', auth()->id()); })->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#2b8c62]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Co-Author Applications</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\CoAuthorApplication::where('user_id', auth()->id())->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Total Comments</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\Comment::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#403D39]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Author Dashboard</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Manage your thesis and co-author applications</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('dashboard') }}" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">View Feed</p>
                                <p class="text-xs text-[#CCC5B9]">Browse all thesis</p>
                            </div>
                        </a>

                        <a href="{{ route('co-author-application.create') }}" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">Apply as Author</p>
                                <p class="text-xs text-[#CCC5B9]">Submit your thesis</p>
                            </div>
                        </a>

                        <a href="{{ route('author.recommendation.create') }}" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">Recommend Co-Authors</p>
                                <p class="text-xs text-[#CCC5B9]">Add members to author team</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
