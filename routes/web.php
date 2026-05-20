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
    $theses = \App\Models\Thesis::with(['user', 'coAuthors'])
        ->orderBy('created_at', 'desc')
        ->get();

    $stats = [
        'total' => $theses->count(),
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->filter()->unique()->count(),
        'downloads' => $theses->count() * 15, // Estimate based on thesis count
    ];

    return view('welcome', compact('theses', 'stats'));
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
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->unique()->count(),
    ];

    return view('author.feed', compact('theses', 'stats'));
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

Route::get('/author/recommend', [App\Http\Controllers\AuthorRecommendationController::class, 'create'])
    ->middleware(['auth'])
    ->name('author.recommendation.create');

Route::post('/author/recommend', [App\Http\Controllers\AuthorRecommendationController::class, 'store'])
    ->middleware(['auth'])
    ->name('author.recommendation.store');

Route::get('/author/team', [App\Http\Controllers\AuthorRecommendationController::class, 'team'])
    ->middleware(['auth'])
    ->name('author.team');

Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isAuthor()) {
            return redirect()->route('author.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

Route::get('/user/dashboard', function () {
    $user = auth()->user();
    $theses = \App\Models\Thesis::with(['user', 'comments.user', 'comments.replies.user', 'reactions.user', 'coAuthors', 'savedByUsers'])
        ->get()
        ->sortByDesc(function ($thesis) use ($user) {
            // Sort by saved status first (saved = 1, not saved = 0)
            $isSaved = $thesis->savedByUsers()->where('users.id', $user->id)->exists() ? 1 : 0;
            // Then sort by created_at
            return [$isSaved, $thesis->created_at->timestamp];
        });

    $stats = [
        'total' => $theses->count(),
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->unique()->count(),
    ];

    return view('dashboard', compact('theses', 'stats'));
})->middleware(['auth'])->name('user.dashboard');

Route::post('/thesis/{thesis}/save', [\App\Http\Controllers\DashboardController::class, 'saveThesis'])
    ->middleware(['auth'])
    ->name('thesis.save');

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

Route::get('/admin/theses/{thesis}/edit', [App\Http\Controllers\Admin\ThesisController::class, 'edit'])
    ->middleware(['auth'])
    ->name('admin.theses.edit');

Route::put('/admin/theses/{thesis}', [App\Http\Controllers\Admin\ThesisController::class, 'update'])
    ->middleware(['auth'])
    ->name('admin.theses.update');

Route::get('/admin/feed', function () {
    $theses = App\Models\Thesis::with(['user', 'comments.user', 'comments.replies.user', 'reactions.user', 'coAuthors'])
        ->orderBy('created_at', 'desc')
        ->get();

    $stats = [
        'total' => $theses->count(),
        'years' => $theses->pluck('thesis_date')->map(fn($date) => $date->format('Y'))->unique()->count(),
    ];

    return view('admin.feed', compact('theses', 'stats'));
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

Route::get('/admin/author-team', [App\Http\Controllers\AuthorRecommendationController::class, 'userTeam'])
    ->middleware(['auth'])
    ->name('admin.author.team');

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

Route::get('/admin/author-recommendations', [App\Http\Controllers\AuthorRecommendationController::class, 'index'])
    ->middleware(['auth'])
    ->name('author.recommendations.index');

Route::post('/author-recommendations/{recommendation}/approve', [App\Http\Controllers\AuthorRecommendationController::class, 'approve'])
    ->middleware(['auth'])
    ->name('author.recommendation.approve');

Route::post('/author-recommendations/{recommendation}/reject', [App\Http\Controllers\AuthorRecommendationController::class, 'reject'])
    ->middleware(['auth'])
    ->name('author.recommendation.reject');

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
        ->where('id', '!=', auth()->id())
        ->limit(10)
        ->get(['id', 'name', 'email', 'profile_image_path', 'role']);
    
    return response()->json($users);
})->middleware(['auth'])
    ->name('search-users');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
