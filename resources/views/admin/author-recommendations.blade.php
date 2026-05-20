<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Author Recommendations</h2>
                    <p class="text-[#CCC5B9] mt-1">Review and manage author team member recommendations</p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if ($recommendations->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recommendations as $recommendation)
                                <div class="bg-[#FFFCF2] rounded-xl p-6 border border-[#CCC5B9]/20">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-start gap-4">
                                            <div class="h-12 w-12 rounded-full bg-[#CCC5B9]/20 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-semibold text-[#403D39]">
                                                    {{ $recommendation->recommendedUser ? substr($recommendation->recommendedUser->name, 0, 1) : ($recommendation->recommended_name ? substr($recommendation->recommended_name, 0, 1) : '?') }}
                                                </span>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-[#252422]">
                                                    {{ $recommendation->recommendedUser ? $recommendation->recommendedUser->name : $recommendation->recommended_name }}
                                                </h3>
                                                <p class="text-sm text-[#CCC5B9]">
                                                    {{ $recommendation->recommendedUser ? $recommendation->recommendedUser->email : $recommendation->recommended_email }}
                                                </p>
                                                <p class="text-sm text-[#403D39] mt-1">
                                                    Recommended by: {{ $recommendation->recommender->name }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($recommendation->status === 'pending') bg-[#FFFCF2] text-[#403D39] border border-[#CCC5B9]/20
                                            @elseif ($recommendation->status === 'approved') bg-[#EB5E28] text-white
                                            @else bg-[#CCC5B9] text-white @endif">
                                            {{ ucfirst($recommendation->status) }}
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-[#252422] mb-2">Reason for Recommendation</h4>
                                        <p class="text-sm text-[#403D39]">{{ $recommendation->reason }}</p>
                                    </div>

                                    <div class="mb-4">
                                        @if ($recommendation->recommendedUser)
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Existing User
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                New Member (No Account)
                                            </span>
                                        @endif
                                    </div>

                                    @if ($recommendation->rejection_reason)
                                        <div class="mb-4 p-3 bg-white rounded-lg border border-[#CCC5B9]/20">
                                            <p class="text-xs text-[#CCC5B9]">Rejection Reason:</p>
                                            <p class="text-sm text-[#252422]">{{ $recommendation->rejection_reason }}</p>
                                        </div>
                                    @endif

                                    @if ($recommendation->status === 'pending')
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('author.recommendation.approve', $recommendation) }}">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-[#EB5E28] text-white rounded-lg text-sm font-medium hover:bg-[#d45220] transition-colors">
                                                    Approve
                                                </button>
                                            </form>

                                            <button onclick="toggleRejectForm({{ $recommendation->id }})" class="px-4 py-2 bg-[#403D39] text-white rounded-lg text-sm font-medium hover:bg-[#252422] transition-colors">
                                                Reject
                                            </button>
                                        </div>

                                        <div id="reject-form-{{ $recommendation->id }}" class="hidden mt-4">
                                            <form method="POST" action="{{ route('author.recommendation.reject', $recommendation) }}">
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
                                                    <button type="button" onclick="toggleRejectForm({{ $recommendation->id }})" class="px-4 py-2 bg-[#FFFCF2] text-[#403D39] rounded-lg text-sm font-medium hover:bg-white transition-colors border border-[#CCC5B9]/20">
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
                            <p class="text-[#CCC5B9]">No author recommendations yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRejectForm(recommendationId) {
            const form = document.getElementById('reject-form-' + recommendationId);
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
