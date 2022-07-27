<?php

//Route::view('/', 'welcome');
Route::get('/', 'HomeController@index');
Route::get('/check_slug', 'ProjectsController@check_slug')->name('projects.check_slug');
Route::get('/projects/create', 'ProjectsController@create');
Route::get('/projects/{project_slug}', 'ProjectsController@show');
Route::get('/projects/{project_slug}/summary', 'ProjectsController@summary');

Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Auth::routes();

// Social logins - added (not part of Auth::routes())
Route::get('login/google', 'Auth\LoginController@google');
Route::get('login/google/redirect', 'Auth\LoginController@googleRedirect');
Route::get('login/facebook', 'Auth\LoginController@facebook');
Route::get('login/facebook/redirect', 'Auth\LoginController@facebookRedirect');
Route::get('login/twitter', 'Auth\LoginController@twitter');
Route::get('login/twitter/redirect', 'Auth\LoginController@twitterRedirect');
Route::get('login/linkedin', 'Auth\LoginController@linkedin');
Route::get('login/linkedin/redirect', 'Auth\LoginController@linkedinRedirect');
Route::get('login/github', 'Auth\LoginController@github');
Route::get('login/github/redirect', 'Auth\LoginController@githubRedirect');
Route::get('login/microsoft', 'Auth\LoginController@microsoft');
Route::get('login/microsoft/redirect', 'Auth\LoginController@microsoftRedirect');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Domains
    Route::delete('domains/destroy', 'DomainsController@massDestroy')->name('domains.massDestroy');
    Route::post('domains/media', 'DomainsController@storeMedia')->name('domains.storeMedia');
    Route::post('domains/ckmedia', 'DomainsController@storeCKEditorImages')->name('domains.storeCKEditorImages');
    Route::resource('domains', 'DomainsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::resource('questions', 'QuestionsController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::resource('countries', 'CountriesController');

    // Projects
    Route::delete('projects/destroy', 'ProjectsController@massDestroy')->name('projects.massDestroy');
    Route::post('projects/media', 'ProjectsController@storeMedia')->name('projects.storeMedia');
    Route::post('projects/ckmedia', 'ProjectsController@storeCKEditorImages')->name('projects.storeCKEditorImages');
    Route::resource('projects', 'ProjectsController');

    // Answers
    Route::delete('answers/destroy', 'AnswersController@massDestroy')->name('answers.massDestroy');
    Route::resource('answers', 'AnswersController');

    // Recommendations
    Route::delete('recommendations/destroy', 'RecommendationsController@massDestroy')->name('recommendations.massDestroy');
    Route::post('recommendations/media', 'RecommendationsController@storeMedia')->name('recommendations.storeMedia');
    Route::post('recommendations/ckmedia', 'RecommendationsController@storeCKEditorImages')->name('recommendations.storeCKEditorImages');
    Route::resource('recommendations', 'RecommendationsController');

    // Blocklist
    Route::delete('blocklists/destroy', 'BlocklistController@massDestroy')->name('blocklists.massDestroy');
    Route::resource('blocklists', 'BlocklistController');

    // Results
    Route::delete('results/destroy', 'ResultsController@massDestroy')->name('results.massDestroy');
    Route::resource('results', 'ResultsController');

    // Topics
    Route::delete('topics/destroy', 'TopicsController@massDestroy')->name('topics.massDestroy');
    Route::resource('topics', 'TopicsController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/check_slug', 'ProjectsController@check_slug')->name('projects.check_slug');
    Route::get('/assessment/{project_slug}', 'AssessmentController@domainmap')->name('assessment.domainmap');
    Route::post('/assessment/{project_slug}/answer/{question_id}', 'AssessmentController@answer')->name('assessment.answer');
    Route::get('/assessment/{project_slug}/{domain_slug}', 'AssessmentController@show')->name('assessment.show');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Domains
    Route::delete('domains/destroy', 'DomainsController@massDestroy')->name('domains.massDestroy');
    Route::post('domains/media', 'DomainsController@storeMedia')->name('domains.storeMedia');
    Route::post('domains/ckmedia', 'DomainsController@storeCKEditorImages')->name('domains.storeCKEditorImages');
    Route::resource('domains', 'DomainsController');

    // Questions
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::post('questions/media', 'QuestionsController@storeMedia')->name('questions.storeMedia');
    Route::post('questions/ckmedia', 'QuestionsController@storeCKEditorImages')->name('questions.storeCKEditorImages');
    Route::resource('questions', 'QuestionsController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::resource('countries', 'CountriesController');

    // Projects
    Route::delete('projects/destroy', 'ProjectsController@massDestroy')->name('projects.massDestroy');
    Route::post('projects/media', 'ProjectsController@storeMedia')->name('projects.storeMedia');
    Route::post('projects/ckmedia', 'ProjectsController@storeCKEditorImages')->name('projects.storeCKEditorImages');
    Route::resource('projects', 'ProjectsController')->parameters([
        'projects' => 'projects:slug',
    ]);

    // Answers
    Route::delete('answers/destroy', 'AnswersController@massDestroy')->name('answers.massDestroy');
    Route::resource('answers', 'AnswersController');

    // Recommendations
    Route::delete('recommendations/destroy', 'RecommendationsController@massDestroy')->name('recommendations.massDestroy');
    Route::post('recommendations/media', 'RecommendationsController@storeMedia')->name('recommendations.storeMedia');
    Route::post('recommendations/ckmedia', 'RecommendationsController@storeCKEditorImages')->name('recommendations.storeCKEditorImages');
    Route::resource('recommendations', 'RecommendationsController');

    // Blocklist
    Route::delete('blocklists/destroy', 'BlocklistController@massDestroy')->name('blocklists.massDestroy');
    Route::resource('blocklists', 'BlocklistController');

    // Results
    Route::delete('results/destroy', 'ResultsController@massDestroy')->name('results.massDestroy');
    Route::resource('results', 'ResultsController');

    // Topics
    //Route::delete('topics/destroy', 'TopicsController@massDestroy')->name('topics.massDestroy');
    Route::resource('topics', 'TopicsController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
