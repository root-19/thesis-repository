<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Total Users</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\User::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Verified Users</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\User::whereNotNull('email_verified_at')->count() }}</p>
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
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Pending Verification</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\User::whereNull('email_verified_at')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#CCC5B9]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#CCC5B9] uppercase tracking-wider">Admin Accounts</p>
                            <p class="text-3xl font-bold text-[#252422] mt-1">{{ App\Models\User::where('role', 'admin')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-[#FFFCF2] rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#403D39]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Admin Controls</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Manage users and system settings</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="#" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">Add User</p>
                                <p class="text-xs text-[#CCC5B9]">Create new account</p>
                            </div>
                        </a>

                        <a href="#" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">Manage Users</p>
                                <p class="text-xs text-[#CCC5B9]">View and edit users</p>
                            </div>
                        </a>

                        <a href="#" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">Manage Thesis</p>
                                <p class="text-xs text-[#CCC5B9]">Upload and edit thesis</p>
                            </div>
                        </a>

                        <a href="{{ route('author.recommendations.index') }}" class="group flex items-center p-4 rounded-xl border border-[#CCC5B9]/20 hover:border-[#EB5E28] hover:bg-[#FFFCF2] transition-all">
                            <div class="w-10 h-10 bg-[#FFFCF2] rounded-lg flex items-center justify-center group-hover:bg-[#EB5E28] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#EB5E28] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-[#252422] group-hover:text-[#EB5E28] transition-colors">Author Recommendations</p>
                                <p class="text-xs text-[#CCC5B9]">Review author team requests</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
