<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Author Team</h2>
                    <p class="text-[#CCC5B9] mt-1">View all approved authors and their team members</p>
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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Source</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CCC5B9]/20">
                                    @foreach ($authors as $author)
                                        @php
                                            $coAuthorApp = \App\Models\CoAuthorApplication::where('user_id', $author->id)
                                                ->where('status', 'approved')
                                                ->first();
                                            $authorRecommendation = \App\Models\AuthorRecommendation::where('recommended_user_id', $author->id)
                                                ->where('status', 'approved')
                                                ->first();
                                            
                                            $source = '';
                                            if ($coAuthorApp) {
                                                $source = 'Co-Author Application';
                                                $title = $coAuthorApp->title;
                                                $department = $coAuthorApp->department;
                                            } elseif ($authorRecommendation) {
                                                $source = 'Author Recommendation';
                                                // Check if the recommended author is also part of a co-author application
                                                $linkedCoAuthorApp = \App\Models\CoAuthorApplication::where('user_id', $author->id)->where('status', 'approved')->first();
                                                if ($linkedCoAuthorApp) {
                                                    $title = $linkedCoAuthorApp->title;
                                                    $department = $linkedCoAuthorApp->department;
                                                } else {
                                                    $title = '-';
                                                    $department = '-';
                                                }
                                            } else {
                                                $source = 'Direct Author';
                                                $title = $author->thesis_title ?? '-';
                                                $department = $author->department ?? '-';
                                            }
                                        @endphp
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
                                                <div class="text-sm text-[#403D39]">{{ $department }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-[#403D39]">{{ $title }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($source === 'Co-Author Application') bg-blue-100 text-blue-800
                                                    @elseif($source === 'Author Recommendation') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $source }}
                                                </span>
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
