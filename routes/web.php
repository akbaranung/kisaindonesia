<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ManageGenres;
use App\Livewire\Admin\ManagePremiumRequests;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\FollowingFeed;
use App\Livewire\FullNotificationList;
use App\Livewire\Home;
use App\Livewire\KisaBean\Topup;
use App\Livewire\KisaBean\TransactionHistory;
use App\Livewire\ManagePenNames;
use App\Livewire\PenNameProfile;
use App\Livewire\Profile;
use App\Livewire\Story\ApplyPremium;
use App\Livewire\Story\Chapter\ChapterEditor;
use App\Livewire\Story\Chapter\ManageStoryChapters;
use App\Livewire\Story\MyLibrary;
use App\Livewire\Story\MyStories;
use App\Livewire\Story\StoryChapters;
use App\Livewire\Story\StoryDetail;
use App\Livewire\Story\StoryReader;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', Home::class)->name('home');
Route::get('/stories/{story:slug}', StoryDetail::class)->name('stories.read');

// tampilan pemberitahuan verifikasi
Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {

    $user = User::findOrFail($request->id);

    if ($user->hasVerifiedEmail()) {
        abort(403, 'Tautan verifikasi ini sudah tidak berlaku karena email kamu sudah terverifikasi.');
    }

    if (! hash_equals(
        sha1($user->getEmailForVerification()),
        $request->hash
    )) {
        abort(403);
    }

    $user->markEmailAsVerified();

    return redirect('/login')
        ->with('success', 'Email berhasil diverifikasi.');
})->middleware(['signed'])->name('verification.verify');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');

    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('success', 'Sampai jumpa lagi, Bro!');
    })->name('logout');

    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/feed', FollowingFeed::class)->name('feed');
    Route::get('/my-stories', MyStories::class)->name('my-stories');
    Route::get('/library', MyLibrary::class)->name('library');

    Route::get('/topup', Topup::class)->name('topup');

    Route::get('/my-stories/{story:slug}/manage', StoryChapters::class)->name('stories.manage');
    Route::get('/stories/{story}/chapters', ManageStoryChapters::class)->name('stories.chapters');
    Route::get('/stories/{story}/characters', ManageStoryChapters::class)->name('stories.characters');
    Route::get('/stories/{story:slug}/chapters/{chapter:slug}', StoryReader::class)->name('stories.chapter.read');
    Route::get('/stories/{story}/chapters/{chapter}/editor', ChapterEditor::class)->name('chapters.editor');
    Route::get('/my-pen-names', ManagePenNames::class)->name('pen-names.index');
    Route::get('/author/{slug}', PenNameProfile::class)->name('pen-name.show');

    Route::get('/monetization/apply', ApplyPremium::class)->name('monetization.apply');

    Route::get('/kisa-bean/history', TransactionHistory::class)->name('kisa-bean.history');
    Route::get('/notifications', FullNotificationList::class)->name('notifications.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/genres', ManageGenres::class)->name('genres');
    Route::get('/users', ManageUsers::class)->name('users');
    Route::get('/premium-requests', ManagePremiumRequests::class)->name('premium-requests');

    Route::get('/chapters/{id}/preview', function ($id) {
        $chapter = Chapter::with('story')->findOrFail($id);

        return view('admin.chapters.preview', compact('chapter'));
    })->name('chapters.preview');
});

Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');
