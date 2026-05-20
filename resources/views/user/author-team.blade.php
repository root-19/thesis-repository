<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Author Team</h2>
                    <p class="text-[#CCC5B9] mt-1">View all authors and their team members</p>
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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Thesis Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Team Members (Co-Authors)</th>
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
                                                <div class="text-sm text-[#403D39]">{{ $author->thesis_title ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $theses = \App\Models\Thesis::where('user_id', $author->id)->with('coAuthors')->get();
                                                    $coAuthors = collect();
                                                    foreach ($theses as $thesis) {
                                                        foreach ($thesis->coAuthors as $coAuthor) {
                                                            $coAuthors->push($coAuthor);
                                                        }
                                                    }
                                                    $coAuthors = $coAuthors->unique('id');
                                                @endphp
                                                @if ($coAuthors->count() > 0)
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($coAuthors as $coAuthor)
                                                            <div class="flex items-center gap-2 bg-[#FFFCF2] rounded-lg px-3 py-2 border border-[#CCC5B9]/20">
                                                                @if ($coAuthor->profile_image_path)
                                                                    <img src="{{ asset('storage/' . $coAuthor->profile_image_path) }}" alt="{{ $coAuthor->name }}" class="h-6 w-6 rounded-full object-cover">
                                                                @else
                                                                    <div class="h-6 w-6 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                                                        <span class="text-xs font-semibold text-[#403D39]">{{ strtoupper(substr($coAuthor->name, 0, 1)) }}</span>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="text-xs font-medium text-[#252422]">{{ $coAuthor->name }}</p>
                                                                    <p class="text-xs text-[#CCC5B9]">{{ $coAuthor->email }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-sm text-[#CCC5B9]">No team members</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-[#CCC5B9]">No authors yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
