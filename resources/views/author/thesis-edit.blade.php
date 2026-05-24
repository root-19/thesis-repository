<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Edit Thesis</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Update thesis details. Your changes will be submitted for admin approval.</p>
                </div>
                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('author.works.update', $thesis) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-[#252422] mb-2">Title</label>
                                <input type="text" id="title" name="title" required
                                    class="block w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    value="{{ old('title', $thesis->title) }}">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-[#252422] mb-2">Description</label>
                                <textarea id="description" name="description" rows="4" required
                                    class="block w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]">{{ old('description', $thesis->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="keywords" class="block text-sm font-medium text-[#252422] mb-2">Keywords</label>
                                <textarea id="keywords" name="keywords" rows="2"
                                    class="block w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Enter keywords separated by commas (e.g., machine learning, artificial intelligence, data science)">{{ old('keywords', $thesis->keywords ?? '') }}</textarea>
                                <p class="mt-1 text-xs text-[#CCC5B9]">Keywords will help users find this thesis more easily</p>
                                @error('keywords')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="thesis_date" class="block text-sm font-medium text-[#252422] mb-2">Thesis Date</label>
                                <input type="date" id="thesis_date" name="thesis_date" required
                                    class="block w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    value="{{ old('thesis_date', $thesis->thesis_date->format('Y-m-d')) }}">
                                @error('thesis_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('author.works') }}" class="px-4 py-2 rounded-lg border border-[#CCC5B9]/40 text-[#252422] hover:bg-[#FFFCF2] transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" class="px-6 py-2 rounded-lg bg-[#EB5E28] text-white text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
