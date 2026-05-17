<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-[#252422]">Theses Management</h3>
                        <p class="text-sm text-[#CCC5B9] mt-1">Upload and manage thesis documents</p>
                    </div>
                    <a href="{{ route('admin.theses.create') }}" class="px-5 py-2.5 rounded-full bg-[#EB5E28] text-white text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                        Upload Thesis
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#FFFCF2]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Thesis Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Uploaded By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CCC5B9]/20">
                            @forelse($theses as $thesis)
                                <tr class="hover:bg-[#FFFCF2] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-[#252422]">{{ $thesis->title }}</div>
                                        <div class="text-sm text-[#CCC5B9] truncate max-w-xs">{{ $thesis->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $thesis->author }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $thesis->department }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $thesis->thesis_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $thesis->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ asset('storage/' . $thesis->pdf_file_path) }}" target="_blank" class="text-[#2b8c62] hover:text-[#EB5E28] transition-colors text-sm font-medium">
                                                View PDF
                                            </a>
                                            <form method="POST" action="{{ route('admin.theses.destroy', $thesis) }}" onsubmit="return confirm('Are you sure you want to delete this thesis?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[#CCC5B9] hover:text-[#EB5E28] transition-colors text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-[#CCC5B9]">No theses uploaded yet</div>
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
