<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Apply as Co-Author</h2>
                    <p class="text-[#CCC5B9] mt-1">Submit your thesis to become a co-author</p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('co-author-application.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-[#252422] mb-2">Thesis Title</label>
                                <input type="text" name="title" id="title" required
                                    class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Enter thesis title">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-[#252422] mb-2">Description</label>
                                <textarea name="description" id="description" rows="4" required
                                    class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Describe your thesis"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="thesis_date" class="block text-sm font-medium text-[#252422] mb-2">Thesis Date</label>
                                    <input type="date" name="thesis_date" id="thesis_date" required
                                        class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50">
                                </div>

                                <div>
                                    <label for="department" class="block text-sm font-medium text-[#252422] mb-2">Department</label>
                                    <input type="text" name="department" id="department" required
                                        class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                        placeholder="Enter department">
                                </div>
                            </div>

                            <div>
                                <label for="pdf_file" class="block text-sm font-medium text-[#252422] mb-2">PDF File</label>
                                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required
                                    class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50">
                                <p class="text-xs text-[#CCC5B9] mt-1">Maximum file size: 10MB</p>
                            </div>

                            <div>
                                <label for="co_authors_search" class="block text-sm font-medium text-[#252422] mb-2">Add Co-Authors</label>
                                <input type="text" id="co_authors_search" name="co_authors_search"
                                    class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Search users to add as co-authors..."
                                    onkeyup="searchCoAuthors(this.value)">
                                <div id="co_authors_search_results" class="mt-2 space-y-2"></div>
                                <div id="selected_co_authors" class="mt-4 space-y-2"></div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-3 bg-[#EB5E28] text-white rounded-xl text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                                    Submit Application
                                </button>
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
            document.getElementById(`selected-co-author-${userId}`).remove();
        }
    </script>
</x-app-layout>
