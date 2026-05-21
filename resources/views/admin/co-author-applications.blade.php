<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Reseacher Applications</h2>
                    <p class="text-[#CCC5B9] mt-1">Review and manage Researcher applications</p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if ($applications->count() > 0)
                        <div class="space-y-4">
                            @foreach ($applications as $application)
                                <div class="bg-[#FFFCF2] rounded-xl p-6 border border-[#CCC5B9]/20">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-[#252422]">{{ $application->title }}</h3>
                                            <p class="text-sm text-[#CCC5B9]">Submitted by: {{ $application->user->name }} ({{ $application->user->email }})</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($application->status === 'pending') bg-[#FFFCF2] text-[#403D39] border border-[#CCC5B9]/20
                                            @elseif ($application->status === 'approved') bg-[#EB5E28] text-white
                                            @else bg-[#CCC5B9] text-white @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-[#403D39] mb-4">{{ $application->description }}</p>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="bg-white rounded-lg p-3 border border-[#CCC5B9]/20">
                                            <p class="text-xs text-[#CCC5B9]">Thesis Date</p>
                                            <p class="text-sm font-medium text-[#252422]">{{ $application->thesis_date->format('M d, Y') }}</p>
                                        </div>
                                        @if ($application->keywords)
                                            <div class="bg-white rounded-lg p-3 border border-[#CCC5B9]/20">
                                                <p class="text-xs text-[#CCC5B9]">Keywords</p>
                                                <p class="text-sm font-medium text-[#252422]">{{ $application->keywords }}</p>
                                            </div>
                                        @endif
                                        @if ($application->pdf_file_path)
                                            <div class="bg-white rounded-lg p-3 border border-[#CCC5B9]/20">
                                                <p class="text-xs text-[#CCC5B9]">PDF File</p>
                                                <a href="{{ asset('storage/' . $application->pdf_file_path) }}" target="_blank" class="text-sm font-medium text-[#EB5E28] hover:text-[#d45220]">View PDF</a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-[#252422] mb-2">Co-Authors</h4>
                                        @if ($application->coAuthors->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($application->coAuthors as $coAuthor)
                                                    <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 border border-[#CCC5B9]/20">
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
                                            <p class="text-sm text-[#CCC5B9]">No additional co-authors</p>
                                        @endif
                                    </div>

                                    @if ($application->rejection_reason)
                                        <div class="mb-4 p-3 bg-white rounded-lg border border-[#CCC5B9]/20">
                                            <p class="text-xs text-[#CCC5B9]">Rejection Reason:</p>
                                            <p class="text-sm text-[#252422]">{{ $application->rejection_reason }}</p>
                                        </div>
                                    @endif

                                    @if ($application->status === 'pending')
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('co-author-application.approve', $application) }}">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-[#EB5E28] text-white rounded-lg text-sm font-medium hover:bg-[#d45220] transition-colors">
                                                    Approve & Upload Thesis
                                                </button>
                                            </form>

                                            <button onclick="toggleRejectForm({{ $application->id }})" class="px-4 py-2 bg-[#403D39] text-white rounded-lg text-sm font-medium hover:bg-[#252422] transition-colors">
                                                Reject
                                            </button>
                                        </div>

                                        <div id="reject-form-{{ $application->id }}" class="hidden mt-4">
                                            <form method="POST" action="{{ route('co-author-application.reject', $application) }}">
                                                @csrf
                                                <div>
                                                    <label class="block text-sm font-medium text-[#252422] mb-2">Rejection Reason</label>
                                                    <textarea name="rejection_reason" rows="2" required
                                                        class="w-full rounded-lg border-[#CCC5B9]/40 px-3 py-2 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                                        placeholder="Enter rejection reason"></textarea>
                                                </div>
                                                <div class="flex gap-2 mt-2">
                                                    <button type="submit" class="px-4 py-2 bg-[#CCC5B9] text-white rounded-lg text-sm font-medium hover:bg-[#b5b4b0] transition-colors">
                                                        Submit Rejection
                                                    </button>
                                                    <button type="button" onclick="toggleRejectForm({{ $application->id }})" class="px-4 py-2 bg-[#FFFCF2] text-[#403D39] rounded-lg text-sm font-medium hover:bg-white transition-colors border border-[#CCC5B9]/20">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-[#CCC5B9]">No co-author applications yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRejectForm(applicationId) {
            const form = document.getElementById('reject-form-' + applicationId);
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
