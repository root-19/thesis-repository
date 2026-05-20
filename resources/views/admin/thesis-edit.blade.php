<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Edit Thesis</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Update thesis details and PDF file</p>
                </div>
                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.theses.update', $thesis) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $thesis->title)" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4" required
                                    class="block mt-1 w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]">{{ old('description', $thesis->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="keywords" :value="__('Keywords')" />
                                <textarea id="keywords" name="keywords" rows="2"
                                    class="block mt-1 w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Enter keywords separated by commas (e.g., machine learning, artificial intelligence, data science)">{{ old('keywords', $thesis->keywords ?? '') }}</textarea>
                                <p class="mt-1 text-xs text-[#CCC5B9]">Keywords will help users find this thesis more easily</p>
                                <x-input-error :messages="$errors->get('keywords')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="thesis_date" :value="__('Thesis Date')" />
                                    <input id="thesis_date" name="thesis_date" type="date" required
                                        class="block mt-1 w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                        value="{{ old('thesis_date', $thesis->thesis_date->format('Y-m-d')) }}" />
                                    <x-input-error :messages="$errors->get('thesis_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="department" :value="__('Department')" />
                                    <x-text-input id="department" class="block mt-1 w-full" type="text" name="department" :value="old('department', $thesis->department)" required />
                                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="author" :value="__('Primary Author')" />
                                <x-text-input id="author" class="block mt-1 w-full" type="text" name="author" :value="old('author', $thesis->author)" required />
                                <x-input-error :messages="$errors->get('author')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="co_authors_search" :value="__('Add Co-Authors (will be displayed in thesis)')" />
                                <input type="text" id="co_authors_search" name="co_authors_search"
                                    class="block mt-1 w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Search authors to add..."
                                    onkeyup="searchCoAuthors(this.value)">
                                <div id="co_authors_search_results" class="mt-2 space-y-2"></div>
                                <div id="selected_co_authors" class="mt-4 space-y-2">
                                    @foreach ($thesis->coAuthors as $coAuthor)
                                        <div id="selected-co-author-{{ $coAuthor->id }}" class="flex items-center justify-between bg-white rounded-xl p-3 border border-[#CCC5B9]/20">
                                            <div class="flex items-center gap-3">
                                                @if ($coAuthor->profile_image_path)
                                                    <img src="/storage/{{ $coAuthor->profile_image_path }}" alt="{{ $coAuthor->name }}" class="h-8 w-8 rounded-full object-cover">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                                        <span class="text-xs font-semibold text-[#403D39]">{{ strtoupper(substr($coAuthor->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-[#252422]">{{ $coAuthor->name }}</p>
                                                    <p class="text-xs text-[#CCC5B9]">Will be displayed as co-author</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="removeCoAuthor({{ $coAuthor->id }})" class="px-3 py-1 bg-[#CCC5B9] text-white rounded-lg text-xs font-medium hover:bg-[#b5b4b0] transition-colors">
                                                Remove
                                            </button>
                                            <input type="hidden" name="co_authors[]" value="{{ $coAuthor->id }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <x-input-label for="pdf_file" :value="__('PDF File')" />
                                <input id="pdf_file" name="pdf_file" type="file" accept=".pdf"
                                    class="block mt-1 w-full text-sm text-[#403D39] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#FFFCF2] file:text-[#252422] hover:file:bg-[#CCC5B9]/30" />
                                <p class="mt-1 text-xs text-[#CCC5B9]">Leave blank to keep current file. Maximum file size: 10MB</p>
                                @if ($thesis->pdf_file_path)
                                    <p class="mt-2 text-sm text-[#403D39]">Current file: <a href="{{ asset('storage/' . $thesis->pdf_file_path) }}" target="_blank" class="text-[#2b8c62] hover:text-[#EB5E28] transition-colors font-medium">View PDF</a></p>
                                @endif
                                <x-input-error :messages="$errors->get('pdf_file')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.theses') }}" class="px-5 py-2.5 rounded-full border border-[#CCC5B9]/40 text-[#403D39] text-sm font-semibold hover:bg-[#FFFCF2] transition-colors">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Update Thesis') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedCoAuthors = [];
        let searchTimeout;

        // Initialize with existing co-authors
        @foreach ($thesis->coAuthors as $coAuthor)
            selectedCoAuthors.push({{ $coAuthor->id }});
        @endforeach

        function searchCoAuthors(query) {
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                document.getElementById('co_authors_search_results').innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/search-users?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        const resultsDiv = document.getElementById('co_authors_search_results');
                        resultsDiv.innerHTML = '';

                        users.forEach(user => {
                            if (!selectedCoAuthors.includes(user.id) && user.id !== {{ auth()->id() }}) {
                                const userDiv = document.createElement('div');
                                userDiv.className = 'flex items-center justify-between bg-[#FFFCF2] rounded-xl p-3 hover:bg-white transition-colors cursor-pointer';
                                userDiv.innerHTML = `
                                    <div class="flex items-center gap-3">
                                        ${user.profile_image_path 
                                            ? `<img src="/storage/${user.profile_image_path}" alt="${user.name}" class="h-8 w-8 rounded-full object-cover">`
                                            : `<div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                                                <span class="text-xs font-semibold text-[#403D39]">${user.name.charAt(0).toUpperCase()}</span>
                                            </div>`
                                        }
                                        <div>
                                            <p class="text-sm font-medium text-[#252422]">${user.name}</p>
                                            <p class="text-xs text-[#CCC5B9]">${user.email}</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="addCoAuthor(${user.id}, '${user.name}', '${user.profile_image_path || ''}')" class="px-3 py-1 bg-[#EB5E28] text-white rounded-lg text-xs font-medium hover:bg-[#d45220] transition-colors">
                                        Add
                                    </button>
                                `;
                                resultsDiv.appendChild(userDiv);
                            }
                        });

                        if (resultsDiv.children.length === 0) {
                            resultsDiv.innerHTML = '<p class="text-sm text-[#CCC5B9]">No users found.</p>';
                        }
                    });
            }, 300);
        }

        function addCoAuthor(userId, userName, profileImage) {
            if (selectedCoAuthors.includes(userId)) return;

            selectedCoAuthors.push(userId);

            const selectedDiv = document.getElementById('selected_co_authors');
            const userDiv = document.createElement('div');
            userDiv.className = 'flex items-center justify-between bg-white rounded-xl p-3 border border-[#CCC5B9]/20';
            userDiv.id = `selected-co-author-${userId}`;
            userDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    ${profileImage 
                        ? `<img src="/storage/${profileImage}" alt="${userName}" class="h-8 w-8 rounded-full object-cover">`
                        : `<div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                            <span class="text-xs font-semibold text-[#403D39]">${userName.charAt(0).toUpperCase()}</span>
                        </div>`
                    }
                    <div>
                        <p class="text-sm font-medium text-[#252422]">${userName}</p>
                        <p class="text-xs text-[#CCC5B9]">Will be displayed as co-author</p>
                    </div>
                </div>
                <button type="button" onclick="removeCoAuthor(${userId})" class="px-3 py-1 bg-[#CCC5B9] text-white rounded-lg text-xs font-medium hover:bg-[#b5b4b0] transition-colors">
                    Remove
                </button>
                <input type="hidden" name="co_authors[]" value="${userId}">
            `;
            selectedDiv.appendChild(userDiv);

            document.getElementById('co_authors_search_results').innerHTML = '';
            document.getElementById('co_authors_search').value = '';
        }

        function removeCoAuthor(userId) {
            selectedCoAuthors = selectedCoAuthors.filter(id => id !== userId);
            const element = document.getElementById(`selected-co-author-${userId}`);
            if (element) {
                element.remove();
            }
        }
    </script>
</x-app-layout>
