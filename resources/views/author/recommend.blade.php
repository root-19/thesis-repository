<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h2 class="text-2xl font-bold text-[#252422]">Recommend Author Team Member</h2>
                    <p class="text-[#CCC5B9] mt-1">Recommend existing users or new members to join the author team</p>
                </div>

                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-[#FFFCF2] rounded-xl border border-[#EB5E28]/20">
                            <p class="text-sm text-[#EB5E28]">{{ session('status') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 rounded-xl border border-red-200">
                            <ul class="text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('author.recommendation.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-[#252422] mb-3">Recommendation Type</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="recommendation_type" value="existing_user" 
                                            class="w-4 h-4 text-[#EB5E28] focus:ring-[#EB5E28] border-[#CCC5B9]"
                                            onchange="toggleRecommendationType()" checked>
                                        <span class="text-sm text-[#252422]">Existing User</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="recommendation_type" value="new_user"
                                            class="w-4 h-4 text-[#EB5E28] focus:ring-[#EB5E28] border-[#CCC5B9]"
                                            onchange="toggleRecommendationType()">
                                        <span class="text-sm text-[#252422]">New Member (No Account Yet)</span>
                                    </label>
                                </div>
                            </div>

                            <div id="existing_user_section">
                                <label for="recommended_user_id" class="block text-sm font-medium text-[#252422] mb-2">Select User</label>
                                <input type="text" id="user_search" name="user_search"
                                    class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Search users by name or email..."
                                    onkeyup="searchUsers(this.value)">
                                <div id="user_search_results" class="mt-2 space-y-2"></div>
                                <div id="selected_user" class="mt-4 hidden">
                                    <div class="flex items-center justify-between bg-white rounded-xl p-3 border border-[#CCC5B9]/20">
                                        <div class="flex items-center gap-3">
                                            <div id="selected_user_avatar"></div>
                                            <div>
                                                <p id="selected_user_name" class="text-sm font-medium text-[#252422]"></p>
                                                <p id="selected_user_email" class="text-xs text-[#CCC5B9]"></p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="clearSelectedUser()" class="px-3 py-1 bg-[#CCC5B9] text-white rounded-lg text-xs font-medium hover:bg-[#b5b4b0] transition-colors">
                                            Clear
                                        </button>
                                        <input type="hidden" name="recommended_user_id" id="recommended_user_id">
                                    </div>
                                </div>
                            </div>

                            <div id="new_user_section" class="hidden space-y-4">
                                <div>
                                    <label for="recommended_name" class="block text-sm font-medium text-[#252422] mb-2">Full Name</label>
                                    <input type="text" name="recommended_name" id="recommended_name"
                                        class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                        placeholder="Enter full name">
                                </div>
                                <div>
                                    <label for="recommended_email" class="block text-sm font-medium text-[#252422] mb-2">Email Address</label>
                                    <input type="email" name="recommended_email" id="recommended_email"
                                        class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                        placeholder="Enter email address">
                                </div>
                            </div>

                            <div>
                                <label for="reason" class="block text-sm font-medium text-[#252422] mb-2">Reason for Recommendation</label>
                                <textarea name="reason" id="reason" rows="4" required
                                    class="w-full rounded-xl border-[#CCC5B9]/40 px-4 py-3 text-sm focus:border-[#EB5E28] focus:ring-[#EB5E28] bg-[#FFFCF2]/50 placeholder-[#CCC5B9]"
                                    placeholder="Explain why you recommend this person to join the author team"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-3 bg-[#EB5E28] text-white rounded-xl text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                                    Submit Recommendation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($recommendations->count() > 0)
                <div class="mt-8 bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                    <div class="p-6 border-b border-[#CCC5B9]/20">
                        <h3 class="text-xl font-bold text-[#252422]">Your Recommendations</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($recommendations as $recommendation)
                            <div class="bg-[#FFFCF2] rounded-xl p-4 border border-[#CCC5B9]/20">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-full bg-[#CCC5B9]/20 flex items-center justify-center">
                                            <span class="text-sm font-semibold text-[#403D39]">
                                                {{ $recommendation->recommendedUser ? substr($recommendation->recommendedUser->name, 0, 1) : ($recommendation->recommended_name ? substr($recommendation->recommended_name, 0, 1) : '?') }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-[#252422]">
                                                {{ $recommendation->recommendedUser ? $recommendation->recommendedUser->name : $recommendation->recommended_name }}
                                            </p>
                                            <p class="text-xs text-[#CCC5B9]">
                                                {{ $recommendation->recommendedUser ? $recommendation->recommendedUser->email : $recommendation->recommended_email }}
                                            </p>
                                            <p class="text-sm text-[#403D39] mt-2">{{ $recommendation->reason }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $recommendation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($recommendation->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($recommendation->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        let selectedUserId = null;
        let searchTimeout;

        function toggleRecommendationType() {
            const type = document.querySelector('input[name="recommendation_type"]:checked').value;
            document.getElementById('existing_user_section').classList.toggle('hidden', type === 'new_user');
            document.getElementById('new_user_section').classList.toggle('hidden', type === 'existing_user');
            
            if (type === 'new_user') {
                document.getElementById('recommended_user_id').value = '';
                clearSelectedUser();
            }
        }

        function searchUsers(query) {
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                document.getElementById('user_search_results').innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/search-users?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        const resultsDiv = document.getElementById('user_search_results');
                        resultsDiv.innerHTML = '';

                        users.forEach(user => {
                            if (user.id !== {{ auth()->id() }}) {
                                const userDiv = document.createElement('div');
                                userDiv.className = 'flex items-center justify-between bg-[#FFFCF2] rounded-xl p-3 hover:bg-white transition-colors cursor-pointer';
                                userDiv.innerHTML = `
                                    <div class="flex items-center justify-between bg-[#FFFCF2] rounded-xl p-3 hover:bg-white transition-colors cursor-pointer">
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
                                                <p class="text-xs text-[#EB5E28] font-medium">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="selectUser(${user.id}, '${user.name}', '${user.email}', '${user.profile_image_path || ''}')" class="px-3 py-1 bg-[#EB5E28] text-white rounded-lg text-xs font-medium hover:bg-[#d45220] transition-colors">
                                            Select
                                        </button>
                                    </div>
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

        function selectUser(userId, userName, userEmail, profileImage) {
            selectedUserId = userId;
            document.getElementById('recommended_user_id').value = userId;
            
            document.getElementById('selected_user').classList.remove('hidden');
            document.getElementById('selected_user_name').textContent = userName;
            document.getElementById('selected_user_email').textContent = userEmail;
            
            const avatarDiv = document.getElementById('selected_user_avatar');
            if (profileImage) {
                avatarDiv.innerHTML = `<img src="/storage/${profileImage}" alt="${userName}" class="h-8 w-8 rounded-full object-cover">`;
            } else {
                avatarDiv.innerHTML = `<div class="h-8 w-8 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                    <span class="text-xs font-semibold text-[#403D39]">${userName.charAt(0).toUpperCase()}</span>
                </div>`;
            }

            document.getElementById('user_search_results').innerHTML = '';
            document.getElementById('user_search').value = '';
        }

        function clearSelectedUser() {
            selectedUserId = null;
            document.getElementById('recommended_user_id').value = '';
            document.getElementById('selected_user').classList.add('hidden');
        }
    </script>
</x-app-layout>
