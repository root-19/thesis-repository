@if ($users->count() > 0)
    @foreach ($users as $user)
        <div class="flex items-center justify-between bg-[#FFFCF2] rounded-xl p-4 hover:bg-white transition-colors">
            <div class="flex items-center gap-3">
                @if ($user->profile_image_path)
                    <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                @else
                    <div class="h-10 w-10 rounded-full bg-[#FFFCF2] flex items-center justify-center border border-[#CCC5B9]/20">
                        <span class="text-sm font-semibold text-[#403D39]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-[#252422]">{{ $user->name }}</p>
                    <p class="text-xs text-[#CCC5B9]">{{ $user->email }}</p>
                </div>
            </div>
            <form method="POST" action="/theses/{{ $thesisId }}/co-authors">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit" class="px-3 py-1 bg-[#EB5E28] text-white rounded-lg text-xs font-medium hover:bg-[#d45220] transition-colors">
                    Add
                </button>
            </form>
        </div>
    @endforeach
@else
    <p class="text-sm text-[#CCC5B9]">No users found.</p>
@endif
