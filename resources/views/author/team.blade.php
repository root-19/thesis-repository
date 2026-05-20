<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Author Team</h2>
                    <p class="text-[#CCC5B9] mt-1">View all approved author team members</p>
                </div>

                <div class="p-6">
                    @if ($authors->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-[#FFFCF2]">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Author</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Department</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Recommended By</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Joined Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CCC5B9]/20">
                                    @foreach ($authors as $author)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if ($author->profile_image_path)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $author->profile_image_path) }}" alt="{{ $author->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                                            <span class="text-sm font-semibold text-[#403D39]">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-[#252422]">{{ $author->name }}</div>
                                                        <div class="text-xs text-[#EB5E28]">{{ ucfirst($author->role) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-[#403D39]">{{ $author->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-[#403D39]">{{ $author->department ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $recommendation = \App\Models\AuthorRecommendation::where('recommended_user_id', $author->id)
                                                        ->where('status', 'approved')
                                                        ->with('recommender')
                                                        ->first();
                                                @endphp
                                                @if ($recommendation && $recommendation->recommender)
                                                    <div class="text-sm text-[#403D39]">{{ $recommendation->recommender->name }}</div>
                                                @else
                                                    <div class="text-sm text-[#CCC5B9]">-</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-[#403D39]">{{ $author->created_at->format('M d, Y') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-[#CCC5B9]">No author team members yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
