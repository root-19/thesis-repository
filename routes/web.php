<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $authors = User::query()
        ->select(['name', 'email', 'created_at'])
        ->orderBy('name')
        ->get()
        ->map(function (User $user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'thesis_title' => $user->thesis_title ?? null,
                'department' => $user->department ?? null,
                'year' => optional($user->created_at)->format('Y'),
                'tags' => collect($user->tags ?? [])->filter()->values()->all(),
            ];
        });

    if ($authors->isEmpty()) {
        $authors = collect([
            [
                'name' => 'Dr. Althea Ramirez',
                'email' => 'althea.ramirez@example.com',
                'thesis_title' => 'Adaptive Learning Models for Academic Libraries',
                'department' => 'Information Science',
                'year' => 2025,
                'tags' => ['machine learning', 'academic libraries', 'student success'],
            ],
            [
                'name' => 'Prof. Malik Soriano',
                'email' => 'malik.soriano@example.com',
                'thesis_title' => 'Resilient Microgrid Design for Coastal Communities',
                'department' => 'Electrical Engineering',
                'year' => 2024,
                'tags' => ['sustainability', 'microgrids', 'renewable energy'],
            ],
            [
                'name' => 'Engr. Zhen Liu',
                'email' => 'zhen.liu@example.com',
                'thesis_title' => 'Human-Centered Interfaces for Assistive Robotics',
                'department' => 'Computer Engineering',
                'year' => 2023,
                'tags' => ['human-computer interaction', 'robotics', 'accessibility'],
            ],
            [
                'name' => 'Ma. Sofia Delos Reyes',
                'email' => 'sofia.delosreyes@example.com',
                'thesis_title' => 'Community Mapping for Climate Adaptation Strategies',
                'department' => 'Urban Planning',
                'year' => 2025,
                'tags' => ['climate action', 'urban analytics', 'community research'],
            ],
            [
                'name' => 'Dr. Liam Hutcherson',
                'email' => 'liam.hutcherson@example.com',
                'thesis_title' => 'Biometric Signals for Early Cognitive Decline Detection',
                'department' => 'Biomedical Sciences',
                'year' => 2022,
                'tags' => ['biometrics', 'neuroscience', 'predictive modeling'],
            ],
            [
                'name' => 'Cherise Ocampo',
                'email' => 'cherise.ocampo@example.com',
                'thesis_title' => 'Story-driven Visualizations for Cultural Heritage Archives',
                'department' => 'Digital Humanities',
                'year' => 2024,
                'tags' => ['data storytelling', 'heritage studies', 'visual design'],
            ],
        ]);
    }

    $stats = [
        'total' => $authors->count(),
        'departments' => $authors->pluck('department')->filter()->unique()->count(),
        'years' => $authors->pluck('year')->filter()->unique()->count(),
    ];

    return view('welcome', [
        'authors' => $authors,
        'stats' => $stats,
    ]);
});

Route::get('/author/dashboard', function () {
    return view('author.dashboard');
})->middleware(['auth'])
    ->name('author.dashboard');

Route::get('/author/feed', function () {
    $theses = App\Models\Thesis::with(['user', 'comments.user', 'comments.replies.user', 'reactions.user', 'coAuthors'])
        ->orderBy('created_at', 'desc')
        ->get();

    $stats = [
        'total' => $theses->count(),
        'departments' => $theses->pluck('department')->unique()->count(),
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->unique()->count(),
    ];

    $departments = $theses->pluck('department')->unique()->values();

    return view('author.feed', compact('theses', 'stats', 'departments'));
})->middleware(['auth'])
    ->name('author.feed');

Route::get('/author/inbox', [App\Http\Controllers\Author\MessageController::class, 'index'])
    ->middleware(['auth'])
    ->name('author.inbox');

Route::get('/author/messages/{user}', [App\Http\Controllers\Author\MessageController::class, 'show'])
    ->middleware(['auth'])
    ->name('author.messages.show');

Route::post('/author/messages/{user}', [App\Http\Controllers\Author\MessageController::class, 'store'])
    ->middleware(['auth'])
    ->name('author.messages.send');

Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()?->isAuthor()) {
        return redirect()->route('author.dashboard');
    }

    $theses = App\Models\Thesis::with(['user', 'comments.user', 'comments.replies.user', 'reactions.user', 'coAuthors'])
        ->orderBy('created_at', 'desc')
        ->get();

    $stats = [
        'total' => $theses->count(),
        'departments' => $theses->pluck('department')->unique()->count(),
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->unique()->count(),
    ];

    $departments = $theses->pluck('department')->unique()->values();

    return view('dashboard', compact('theses', 'stats', 'departments'));
})->middleware(['auth'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.users');

Route::get('/admin/inbox', [App\Http\Controllers\Admin\MessageController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.inbox');

Route::get('/admin/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'show'])
    ->middleware(['auth'])
    ->name('admin.messages.show');

Route::post('/admin/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.messages.send');

Route::get('/admin/theses', [App\Http\Controllers\Admin\ThesisController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.theses');

Route::get('/admin/theses/create', [App\Http\Controllers\Admin\ThesisController::class, 'create'])
    ->middleware(['auth'])
    ->name('admin.theses.create');

Route::post('/admin/theses', [App\Http\Controllers\Admin\ThesisController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.theses.store');

Route::delete('/admin/theses/{thesis}', [App\Http\Controllers\Admin\ThesisController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('admin.theses.destroy');

Route::get('/admin/feed', function () {
    $theses = App\Models\Thesis::with(['user', 'comments.user', 'comments.replies.user', 'reactions.user', 'coAuthors'])
        ->orderBy('created_at', 'desc')
        ->get();

    $stats = [
        'total' => $theses->count(),
        'departments' => $theses->pluck('department')->unique()->count(),
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->unique()->count(),
    ];

    $departments = $theses->pluck('department')->unique()->values();

    return view('admin.feed', compact('theses', 'stats', 'departments'));
})->middleware(['auth'])
    ->name('admin.feed');

Route::post('/theses/{thesis}/comments', [App\Http\Controllers\CommentController::class, 'store'])
    ->middleware(['auth'])
    ->name('theses.comments.store');

Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('comments.destroy');

Route::post('/theses/{thesis}/reaction', [App\Http\Controllers\ReactionController::class, 'toggle'])
    ->middleware(['auth'])
    ->name('theses.reaction.toggle');

Route::get('/user/messages', [App\Http\Controllers\UserMessageController::class, 'index'])
    ->middleware(['auth'])
    ->name('user.messages');

Route::get('/user/messages/{author}', [App\Http\Controllers\UserMessageController::class, 'show'])
    ->middleware(['auth'])
    ->name('user.messages.show');

Route::post('/user/messages/{author}', [App\Http\Controllers\UserMessageController::class, 'store'])
    ->middleware(['auth'])
    ->name('user.messages.send');

Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])
    ->middleware(['auth'])
    ->name('notifications.index');

Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
    ->middleware(['auth'])
    ->name('notifications.mark-read');

Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsReadSingle'])
    ->middleware(['auth'])
    ->name('notifications.mark-read.single');

Route::get('/co-author-application', [App\Http\Controllers\CoAuthorApplicationController::class, 'create'])
    ->middleware(['auth'])
    ->name('co-author-application.create');

Route::post('/co-author-application', [App\Http\Controllers\CoAuthorApplicationController::class, 'store'])
    ->middleware(['auth'])
    ->name('co-author-application.store');

Route::get('/admin/co-author-applications', [App\Http\Controllers\CoAuthorApplicationController::class, 'index'])
    ->middleware(['auth'])
    ->name('co-author-applications.index');

Route::post('/co-author-applications/{application}/approve', [App\Http\Controllers\CoAuthorApplicationController::class, 'approve'])
    ->middleware(['auth'])
    ->name('co-author-application.approve');

Route::post('/co-author-applications/{application}/reject', [App\Http\Controllers\CoAuthorApplicationController::class, 'reject'])
    ->middleware(['auth'])
    ->name('co-author-application.reject');

Route::get('/theses/{thesis}/co-authors', [App\Http\Controllers\ThesisCoAuthorController::class, 'create'])
    ->middleware(['auth'])
    ->name('thesis.co-author.create');

Route::get('/theses/{thesis}/co-authors/search', [App\Http\Controllers\ThesisCoAuthorController::class, 'search'])
    ->middleware(['auth'])
    ->name('thesis.co-author.search');

Route::post('/theses/{thesis}/co-authors', [App\Http\Controllers\ThesisCoAuthorController::class, 'store'])
    ->middleware(['auth'])
    ->name('thesis.co-author.store');

Route::delete('/theses/{thesis}/co-authors/{user}', [App\Http\Controllers\ThesisCoAuthorController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('thesis.co-author.destroy');

Route::get('/search-users', function (Request $request) {
    $query = $request->get('q', '');
    
    $users = \App\Models\User::where('name', 'like', "%{$query}%")
        ->where('role', 'author')
        ->where('id', '!=', auth()->id())
        ->limit(10)
        ->get(['id', 'name', 'email', 'profile_image_path']);
    
    return response()->json($users);
})->middleware(['auth'])
    ->name('search-users');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
