<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Users Management</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">View and manage all users and their roles</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#FFFCF2]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Email Verified</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CCC5B9]/20">
                            @forelse($users as $user)
                                <tr class="hover:bg-[#FFFCF2] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-[#252422]">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->isAdmin())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#EB5E28]/10 text-[#EB5E28]">
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#2b8c62]/10 text-[#2b8c62]">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->hasVerifiedEmail())
                                            <span class="inline-flex items-center text-[#2b8c62]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[#CCC5B9]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $user->created_at->format('M d, Y') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-[#CCC5B9]">No users found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
