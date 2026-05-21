<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Apply as a Researcher</h2>
                    <p class="text-[#CCC5B9] mt-1">Choose how you want to contribute to research</p>
                </div>

                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- New Researcher Option -->
                        <a href="{{ route('co-author-application.create') }}" class="group">
                            <div class="bg-[#FFFCF2] rounded-2xl p-6 border border-[#CCC5B9]/20 hover:border-[#EB5E28] transition-all hover:shadow-lg">
                                <div class="h-12 w-12 bg-[#EB5E28]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#EB5E28]/20 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-[#252422] mb-2">New Researcher</h3>
                                <p class="text-sm text-[#403D39]">Submit your own thesis paper with title, description, keywords, and PDF file. You can also add co-researchers to your paper.</p>
                                <div class="mt-4 flex items-center text-[#EB5E28] text-sm font-medium">
                                    <span>Apply Now</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <!-- Co-Researcher Option -->
                        <a href="{{ route('co-researcher-application.search') }}" class="group">
                            <div class="bg-[#FFFCF2] rounded-2xl p-6 border border-[#CCC5B9]/20 hover:border-[#EB5E28] transition-all hover:shadow-lg">
                                <div class="h-12 w-12 bg-[#EB5E28]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#EB5E28]/20 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-[#252422] mb-2">Co-Researcher</h3>
                                <p class="text-sm text-[#403D39]">Search for existing thesis papers and request to become a co-researcher. Your request will be reviewed by the admin.</p>
                                <div class="mt-4 flex items-center text-[#EB5E28] text-sm font-medium">
                                    <span>Search Papers</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
