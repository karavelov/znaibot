<?php
// Admin Routes

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\KlasController;
use App\Http\Controllers\Backend\SubjectController;
use App\Http\Controllers\Backend\SubjectNameController;
use App\Http\Controllers\Backend\SemesterController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\AllergenController;
use App\Http\Controllers\Backend\NfcLogController;
use App\Http\Controllers\Backend\NfcReaderController;
use App\Http\Controllers\Backend\ClubController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\LogsController;
use App\Http\Controllers\Backend\BlogSubCategoryController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\FooterGridThreeController;
use App\Http\Controllers\Backend\FooterGridTwoController;
use App\Http\Controllers\Backend\FooterInfoController;
use App\Http\Controllers\Backend\FooterSocialsController;
use App\Http\Controllers\Backend\GalleryImageController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\SubscribersController;
use App\Http\Controllers\Backend\GalleryController;
use App\Http\Controllers\Backend\QuestionController;
use App\Http\Controllers\Backend\QuizCategoryController;
use App\Http\Controllers\Backend\QuizController;
use App\Http\Controllers\Backend\BirthdayController;
use App\Http\Controllers\Backend\RankController;
use App\Http\Livewire\Quiz\QuizForm;
use App\Http\Livewire\Quiz\QuizList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




// Redirect /bgc to either the dashboard or login page
Route::get('/adm', function () {
    if (Auth::check() && Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('admin.login');
    }
});


Route::controller(AdminController::class)->group(function () {
    Route::get('/adm/login', 'login')->name('admin.login');
});


// [BETA]
Route::middleware(['auth', 'role:admin'])->group(function () {
   
    Route::get('/adm/quizzes', QuizList::class)->name('quizzes');
    Route::get('/adm/quizzes/create', QuizForm::class)->name('quiz.create');
    Route::get('/adm/quizzes/{quiz}/edit', QuizForm::class)->name('quiz.edit');

    // PRIORITY 1
    Route::put('/adm/quizzes/change-status', [QuizController::class, 'changeStatus'])->name('admin.quiz.change-status');
    Route::put('/adm/quizzes/change-public', [QuizController::class, 'changePublicStatus'])->name('admin.quiz.change-public');

    Route::resource('/adm/quizzes', QuizController::class)->names([
        'index' => 'admin.quizzes.index',
        'create' => 'admin.quizzes.create',
        'store' => 'admin.quizzes.store',
        'edit' => 'admin.quizzes.edit',
        'update' => 'admin.quizzes.update',
        'destroy' => 'admin.quizzes.delete',
    ]);

    // PRIORITY 1
    Route::put('/adm/quiz-category/status-change', [QuizCategoryController::class, 'changeStatus'])->name('admin.quiz-category.status-change');

     // QuizCategory
     Route::resource('/adm/quiz-category', QuizCategoryController::class)->names([
        'index' => 'admin.quiz-category.index',
        'create' => 'admin.quiz-category.create',
        'store' => 'admin.quiz-category.store',
        'edit' => 'admin.quiz-category.edit',
        'update' => 'admin.quiz-category.update',
        'destroy' => 'admin.quiz-category.delete',
    ]);

     // Route for questions search
     Route::get('/adm/questions/search', [QuestionController::class, 'search'])->name('admin.questions.search');

     // Route for filtering questions by category
     Route::get('/adm/questions/filter', [QuestionController::class, 'filterByCategory'])->name('admin.questions.filter');

    // QUESTIONS
    Route::resource('/adm/questions', QuestionController::class)->names([
        'index' => 'admin.questions.index',
        'create' => 'admin.questions.create',
        'store' => 'admin.questions.store',
        'edit' => 'admin.questions.edit',
        'update' => 'admin.questions.update',
        'destroy' => 'admin.questions.delete',
    ]);


    // Tests Results for Admin
    // Route::get('/adm/tests', [TestController::class, 'index'])->name('admin.tests.index');

});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/adm/dashboard', 'dashboard')->name('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/adm/profile', 'index')->name('admin.profile');
        Route::post('/adm/profile/update', 'update_profile')->name('admin.profile.update');
        Route::post('/adm/profile/update/password', 'update_password')->name('admin.password.update');
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/adm/slider', SliderController::class)->names([
        'index' => 'admin.slider.index',
        'create' => 'admin.slider.create',
        'store' => 'admin.slider.store',
        'edit' => 'admin.slider.edit',
        'update' => 'admin.slider.update',
        'destroy' => 'admin.slider.delete',
    ]);
});


Route::middleware(['auth', 'role:admin'])->group(function () {

    // PRIORITY 1

    Route::put('/adm/brand/change-status', [BrandController::class, 'changeStatus'])->name('admin.brand.change-status');

    Route::resource('/adm/brand', BrandController::class)->names([
        'index' => 'admin.brand.index',
        'create' => 'admin.brand.create',
        'store' => 'admin.brand.store',
        'edit' => 'admin.brand.edit',
        'update' => 'admin.brand.update',
        'destroy' => 'admin.brand.delete',
    ]);
});



// Ранг / Медали
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('/adm/rank', RankController::class)->names([
        'index' => 'admin.rank.index',
        'create' => 'admin.rank.create',
        'store' => 'admin.rank.store',
        'edit' => 'admin.rank.edit',
        'update' => 'admin.rank.update',
        'destroy' => 'admin.rank.delete',
    ]);
});




Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/adm/settings', 'index')->name('admin.settings.index');
        Route::put('/adm/settings/update', 'update')->name('admin.settings.update');
        Route::put('/adm/settings/email-update', 'emailConfigSettingUpdate')->name('admin.email-settings.update');
        Route::put('/adm/settings/logo-update', 'logoSettingUpdate')->name('admin.logo-setting-update');
    });
});




Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/adm/footer-settings', FooterInfoController::class)->names([
        'index' => 'admin.footer-info.index',
        'update' => 'admin.footer-info.update',

    ]);
});


Route::middleware(['auth', 'role:admin'])->group(function () {

    // PRIORITY 1
    Route::put('/adm/footer-settings-socials/change-status', [FooterSocialsController::class, 'changeStatus'])->name('admin.footer-settings-socials.change-status');

    Route::resource('/adm/footer-settings-socials', FooterSocialsController::class)->names([
        'index' => 'admin.footer-socials.index',
        'create' => 'admin.footer-socials.create',
        'store' => 'admin.footer-socials.store',
        'edit' => 'admin.footer-socials.edit',
        'update' => 'admin.footer-socials.update',
        'destroy' => 'admin.footer-socials.delete',

    ]);
});


Route::middleware(['auth', 'role:admin'])->group(function () {

    // PRIORITY 1
    Route::put('/adm/footer-settings-grid-two/change-status', [FooterGridTwoController::class, 'changeStatus'])->name('admin.footer-settings-grid-two.change-status');
    Route::put('/adm/footer-settings-grid-two/change-title', [FooterGridTwoController::class, 'changeTitle'])->name('admin.footer-settings-grid-two.change-title');

    Route::resource('/adm/footer-settings-grid-two', FooterGridTwoController::class)->names([
        'index' => 'admin.footer-grid-two.index',
        'create' => 'admin.footer-grid-two.create',
        'store' => 'admin.footer-grid-two.store',
        'edit' => 'admin.footer-grid-two.edit',
        'update' => 'admin.footer-grid-two.update',
        'destroy' => 'admin.footer-grid-two.delete',

    ]);
});


Route::middleware(['auth', 'role:admin'])->group(function () {

    // PRIORITY 1
    Route::put('/adm/footer-settings-grid-three/change-status', [FooterGridThreeController::class, 'changeStatus'])->name('admin.footer-settings-grid-three.change-status');
    Route::put('/adm/footer-settings-grid-three/change-title', [FooterGridThreeController::class, 'changeTitle'])->name('admin.footer-settings-grid-three.change-title');

    Route::resource('/adm/footer-settings-grid-three', FooterGridThreeController::class)->names([
        'index' => 'admin.footer-grid-three.index',
        'create' => 'admin.footer-grid-three.create',
        'store' => 'admin.footer-grid-three.store',
        'edit' => 'admin.footer-grid-three.edit',
        'update' => 'admin.footer-grid-three.update',
        'destroy' => 'admin.footer-grid-three.delete',

    ]);
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(SubscribersController::class)->group(function () {
        Route::get('/adm/subscribers', 'index')->name('admin.subscribers.index');
        Route::post('/adm/send-mail', 'sendMail')->name('admin.send-mail');
        Route::delete('/adm/subscribers/{id}', 'destroy')->name('admin.subscribers.delete');
    });
});




/** User routes */
Route::middleware(['auth', 'role:admin'])->group(function () {

    // PRIORITY 1
    Route::put('/adm/users/change-status', [CustomerListController::class, 'changeStatus'])->name('admin.users.change-status');

    Route::controller(CustomerListController::class)->group(function () {
        Route::get('/adm/users', 'index')->name('admin.users.index');
        Route::get('/adm/users/create', 'create')->name('admin.users.create');
        Route::post('/adm/users/store', 'store')->name('admin.users.store');
        Route::get('/adm/users/edit/{id}', 'edit')->name('admin.users.edit');
        Route::put('/adm/users/update/{id}', 'update')->name('admin.users.update');
        Route::post('/adm/users/update/password/{id}', 'update_password')->name('admin.users.password.update');
        Route::delete('/adm/users/delete/{id}', 'delete')->name('admin.users.delete');
    });
});



/** Club routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::put('/adm/clubs/status-change', [ClubController::class, 'changeStatus'])->name('admin.clubs.status-change');
    Route::post('/adm/clubs/{id}/students/add', [ClubController::class, 'addStudent'])->name('admin.clubs.add-student');
    Route::delete('/adm/clubs/{id}/students/{userId}/remove', [ClubController::class, 'removeStudent'])->name('admin.clubs.remove-student');

    Route::resource('/adm/clubs', ClubController::class)->names([
        'index'   => 'admin.clubs.index',
        'create'  => 'admin.clubs.create',
        'store'   => 'admin.clubs.store',
        'edit'    => 'admin.clubs.edit',
        'update'  => 'admin.clubs.update',
        'destroy' => 'admin.clubs.delete',
    ]);
});

/** Klas routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/klasses/{id}/delete', [KlasController::class, 'destroy'])->name('admin.klasses.delete');
    Route::delete('/adm/klasses/{klasId}/student/{userId}/remove', [KlasController::class, 'removeStudent'])->name('admin.klasses.remove-student');
    Route::delete('/adm/klasses/{klasId}/homeroom/remove', [KlasController::class, 'removeHomeroom'])->name('admin.klasses.remove-homeroom');

    Route::resource('/adm/klasses', KlasController::class)->names([
        'index'  => 'admin.klasses.index',
        'create' => 'admin.klasses.create',
        'store'  => 'admin.klasses.store',
        'edit'   => 'admin.klasses.edit',
        'update' => 'admin.klasses.update',
    ]);
});

/** NFC routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/nfc-logs',                    [NfcLogController::class, 'index'])->name('admin.nfc-logs.index');
    Route::get('/adm/nfc-logs/presence',           [NfcLogController::class, 'presence'])->name('admin.nfc-logs.presence');
    Route::get('/adm/nfc-logs/user/{user}',        [NfcLogController::class, 'userStats'])->name('admin.nfc-logs.user-stats');
    Route::get('/adm/nfc-logs/late',               [NfcLogController::class, 'lateStats'])->name('admin.nfc-logs.late');

    Route::resource('/adm/nfc-readers', NfcReaderController::class)->names([
        'index'   => 'admin.nfc-readers.index',
        'create'  => 'admin.nfc-readers.create',
        'store'   => 'admin.nfc-readers.store',
        'edit'    => 'admin.nfc-readers.edit',
        'update'  => 'admin.nfc-readers.update',
        'destroy' => 'admin.nfc-readers.destroy',
    ]);
});

/** Birthday routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/birthdays', [BirthdayController::class, 'index'])->name('admin.birthdays.index');
});

/** Allergen routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/allergens/dashboard',                           [AllergenController::class, 'dashboard'])->name('admin.allergens.dashboard');
    Route::post('/adm/allergens/user/{user}/add',                    [AllergenController::class, 'addUserAllergen'])->name('admin.allergens.user.add');
    Route::delete('/adm/allergens/user/{user}/{allergen}/remove',    [AllergenController::class, 'removeUserAllergen'])->name('admin.allergens.user.remove');

    Route::resource('/adm/allergens', AllergenController::class)->names([
        'index'   => 'admin.allergens.index',
        'create'  => 'admin.allergens.create',
        'store'   => 'admin.allergens.store',
        'edit'    => 'admin.allergens.edit',
        'update'  => 'admin.allergens.update',
        'destroy' => 'admin.allergens.destroy',
    ]);
});

/** Semester routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/semesters', [SemesterController::class, 'index'])->name('admin.semesters.index');
    Route::post('/adm/semesters', [SemesterController::class, 'store'])->name('admin.semesters.store');
});

/** Schedule routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/schedule', [ScheduleController::class, 'index'])->name('admin.schedule.index');
    Route::get('/adm/schedule/{klasId}/{semester}', [ScheduleController::class, 'edit'])->name('admin.schedule.edit');
    Route::put('/adm/schedule/{klasId}/{semester}', [ScheduleController::class, 'update'])->name('admin.schedule.update');
});

/** Subject routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Управление на имена на предмети
    Route::resource('/adm/subject-names', SubjectNameController::class)->names([
        'index'   => 'admin.subject-names.index',
        'create'  => 'admin.subject-names.create',
        'store'   => 'admin.subject-names.store',
        'edit'    => 'admin.subject-names.edit',
        'update'  => 'admin.subject-names.update',
        'destroy' => 'admin.subject-names.destroy',
    ]);

    // Назначения предмет → учител
    Route::resource('/adm/subjects', SubjectController::class)->names([
        'index'   => 'admin.subjects.index',
        'create'  => 'admin.subjects.create',
        'store'   => 'admin.subjects.store',
        'edit'    => 'admin.subjects.edit',
        'update'  => 'admin.subjects.update',
        'destroy' => 'admin.subjects.destroy',
    ]);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/adm/logs', [LogsController::class, 'index'])->name('admin.logs.index');
    Route::delete('/adm/logs/{id}', [LogsController::class, 'destroy'])->name('admin.logs.destroy');
});

/** Blog routes */
Route::middleware(['auth', 'role:admin'])->group(function () {

    // PRIORITY 1
    Route::get('/adm/blog/get-subcategories', [BlogController::class, 'getSubcategories'])->name('admin.blog.get-subcategories');
    Route::post('/adm/product/url-image', [BlogController::class, 'getImageFromUrl'])->name('admin.product.url-image');
    Route::put('/adm/blog-category/status-change', [BlogCategoryController::class, 'changeStatus'])->name('admin.blog-category.status-change');
    Route::put('/adm/blog-sub-category/status-change', [BlogSubCategoryController::class, 'changeStatus'])->name('admin.blog-sub-category.status-change');
    Route::put('/adm/blog/status-change', [BlogController::class, 'changeStatus'])->name('admin.blog.status-change');

    // BlogCategory
    Route::resource('/adm/blog-category', BlogCategoryController::class)->names([
        'index' => 'admin.blog-category.index',
        'create' => 'admin.blog-category.create',
        'store' => 'admin.blog-category.store',
        'edit' => 'admin.blog-category.edit',
        'update' => 'admin.blog-category.update',
        'destroy' => 'admin.blog-category.delete',
    ]);

    // BlogSubCategory
    Route::resource('/adm/blog-sub-category', BlogSubCategoryController::class)->names([
        'index' => 'admin.blog-sub-category.index',
        'create' => 'admin.blog-sub-category.create',
        'store' => 'admin.blog-sub-category.store',
        'edit' => 'admin.blog-sub-category.edit',
        'update' => 'admin.blog-sub-category.update',
        'destroy' => 'admin.blog-sub-category.delete',
    ]);

    Route::resource('/adm/blog', BlogController::class)->names([
        'index' => 'admin.blog.index',
        'create' => 'admin.blog.create',
        'store' => 'admin.blog.store',
        'edit' => 'admin.blog.edit',
        'update' => 'admin.blog.update',
        'destroy' => 'admin.blog.delete',
    ]);




    /** Gallery routes */
    Route::middleware(['auth', 'role:admin'])->group(function () {

        // PRIORITY 1
        Route::put('/adm/gallery/status-change', [GalleryController::class, 'changeStatus'])->name('admin.gallery.status-change');

        Route::resource('/adm/gallery', GalleryController::class)->names([
            'index' => 'admin.gallery.index',
            'create' => 'admin.gallery.create',
            'store' => 'admin.gallery.store',
            'edit' => 'admin.gallery.edit',
            'update' => 'admin.gallery.update',
            'destroy' => 'admin.gallery.delete',
        ]);
    });


    // Gallery Images routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('/adm/gallery-images', GalleryImageController::class)->names([
            'index' => 'admin.gallery-images.index',
            'create' => 'admin.gallery-images.create',
            'store' => 'admin.gallery-images.store',
            'edit' => 'admin.gallery-images.edit',
            'update' => 'admin.gallery-images.update',
            'destroy' => 'admin.gallery-images.delete',
        ]);
    });
});
