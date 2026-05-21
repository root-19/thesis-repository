<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-[#CCC5B9]/20 overflow-hidden">
                <div class="p-6 border-b border-[#CCC5B9]/20 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-[#252422]">Users Management</h3>
                        <p class="text-sm text-[#CCC5B9] mt-1">View and manage all users and their roles</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="text" id="userSearch" placeholder="Search by name, email, or year..." class="px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" onkeyup="filterUsers()">
                        <button onclick="openAddUserModal()" class="px-5 py-2.5 rounded-full bg-[#EB5E28] text-white text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">
                            Add User
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#FFFCF2]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Email Verified</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#403D39] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#CCC5B9]/20">
                            @forelse($users as $user)
                                <tr class="hover:bg-[#FFFCF2] transition-colors user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-year="{{ $user->created_at->format('Y') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-[#252422]">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->isAdmin())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#EB5E28]/10 text-[#EB5E28]">
                                                Admin
                                            </span>
                                        @elseif ($user->isAuthor())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#403D39]/10 text-[#403D39]">
                                                Author
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#2b8c62]/10 text-[#2b8c62]">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->hasVerifiedEmail())
                                            <span class="inline-flex items-center text-[#2b8c62]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[#CCC5B9]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#403D39]">{{ $user->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" class="p-2 rounded-lg bg-[#FFFCF2] text-[#252422] hover:bg-[#EB5E28] hover:text-white transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-[#FFFCF2] text-[#252422] hover:bg-[#EB5E28] hover:text-white transition-colors" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-[#CCC5B9]">No users found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/50" onclick="closeAddUserModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full relative z-10">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Add New User</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Create a new user account</p>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" placeholder="Enter user name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Email</label>
                            <input type="email" name="email" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" placeholder="Enter email address">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" placeholder="Enter password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Role</label>
                            <select name="role" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none">
                                <option value="user">User</option>
                                <option value="author">Author</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="p-6 border-t border-[#CCC5B9]/20 flex justify-end gap-3">
                        <button type="button" onclick="closeAddUserModal()" class="px-5 py-2.5 rounded-full bg-[#CCC5B9]/10 text-[#403D39] text-sm font-semibold hover:bg-[#CCC5B9]/20 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-full bg-[#EB5E28] text-white text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/50" onclick="closeEditUserModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full relative z-10">
                <div class="p-6 border-b border-[#CCC5B9]/20">
                    <h3 class="text-lg font-semibold text-[#252422]">Edit User</h3>
                    <p class="text-sm text-[#CCC5B9] mt-1">Update user information</p>
                </div>
                <form method="POST" action="{{ route('admin.users.update', '') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Name</label>
                            <input type="text" name="name" id="editUserName" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" placeholder="Enter user name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Email</label>
                            <input type="email" name="email" id="editUserEmail" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" placeholder="Enter email address">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] placeholder:text-[#CCC5B9] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none" placeholder="Enter new password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#252422] mb-2">Role</label>
                            <select name="role" id="editUserRole" required class="w-full px-4 py-2 rounded-xl border border-[#CCC5B9]/40 bg-[#FFFCF2] text-[#252422] text-sm focus:border-[#EB5E28] focus:ring-2 focus:ring-[#EB5E28]/10 focus:outline-none">
                                <option value="user">User</option>
                                <option value="author">Author</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="p-6 border-t border-[#CCC5B9]/20 flex justify-end gap-3">
                        <button type="button" onclick="closeEditUserModal()" class="px-5 py-2.5 rounded-full bg-[#CCC5B9]/10 text-[#403D39] text-sm font-semibold hover:bg-[#CCC5B9]/20 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-full bg-[#EB5E28] text-white text-sm font-semibold hover:bg-[#d45220] transition-colors shadow-lg shadow-[#EB5E28]/20">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterUsers() {
            const searchTerm = document.getElementById('userSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const name = row.dataset.name;
                const email = row.dataset.email;
                const year = row.dataset.year;
                
                const matches = name.includes(searchTerm) || email.includes(searchTerm) || year.includes(searchTerm);
                row.style.display = matches ? '' : 'none';
            });
        }

        function openAddUserModal() {
            document.getElementById('addUserModal').classList.remove('hidden');
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.add('hidden');
        }

        function openEditUserModal(userId, userName, userEmail, userRole) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserName').value = userName;
            document.getElementById('editUserEmail').value = userEmail;
            document.getElementById('editUserRole').value = userRole;
            
            // Update form action with user ID
            const form = document.querySelector('#editUserModal form');
            form.action = form.action.replace(/\/$/, '') + '/' + userId;
            
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
