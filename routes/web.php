<?php

use \Vanguard\Http\Controllers\Web\{
   
    CommonCodeController
};

/**
 * Authentication
 */
Route::get('login', 'Auth\LoginController@show');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::group(['middleware' => ['registration', 'guest']], function () {
    Route::get('register', 'Auth\RegisterController@show');
    Route::post('register', 'Auth\RegisterController@register');
});

Route::emailVerification();

Route::group(['middleware' => ['password-reset', 'guest']], function () {
    Route::resetPassword();
});

/**
 * Two-Factor Authentication
 */
Route::group(['middleware' => 'two-factor'], function () {
    Route::get('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@show')->name('auth.token');
    Route::post('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@update')->name('auth.token.validate');
});

/**
 * Social Login
 */
Route::get('auth/{provider}/login', 'Auth\SocialAuthController@redirectToProvider')->name('social.login');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

/**
 * Impersonate Routes
 */
Route::group(['middleware' => 'auth'], function () {
    Route::impersonate();
});

Route::group(['middleware' => ['auth', 'verified']], function () {

    /**
     * Dashboard
     */

    Route::get('/', 'DashboardController@index')->name('dashboard');

    /**
     * User Profile
     */

    Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {
        Route::get('/', 'ProfileController@show')->name('profile');
        Route::get('activity', 'ActivityController@show')->name('profile.activity');
        Route::put('details', 'DetailsController@update')->name('profile.update.details');

        Route::post('avatar', 'AvatarController@update')->name('profile.update.avatar');
        Route::post('avatar/external', 'AvatarController@updateExternal')
            ->name('profile.update.avatar-external');

        Route::put('login-details', 'LoginDetailsController@update')
            ->name('profile.update.login-details');

        Route::get('sessions', 'SessionsController@index')
            ->name('profile.sessions')
            ->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'SessionsController@destroy')
            ->name('profile.sessions.invalidate')
            ->middleware('session.database');
    });

    /**
     * Two-Factor Authentication Setup
     */

    Route::group(['middleware' => 'two-factor'], function () {
        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('two-factor.enable');

        Route::get('two-factor/verification', 'TwoFactorController@verification')
            ->name('two-factor.verification')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/resend', 'TwoFactorController@resend')
            ->name('two-factor.resend')
            ->middleware('throttle:1,1', 'verify-2fa-phone');

        Route::post('two-factor/verify', 'TwoFactorController@verify')
            ->name('two-factor.verify')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('two-factor.disable');
    });
 
    /**
     * Project
     */
    //Route::resource('project', 'ProjectController');
    Route::get('/project', 'ProjectController@index')->name('project.index')->middleware('permission:project.index');
    Route::get('/project/create', 'ProjectController@create')->name('project.create')->middleware('permission:project.create');
    Route::post('/project/store', 'ProjectController@store')->name('project.store')->middleware('permission:project.store');
    Route::get('/project/edit/{id}', 'ProjectController@edit')->name('project.edit')->middleware('permission:project.edit');
    Route::post('/project/update/{id}', 'ProjectController@update')->name('project.update')->middleware('permission:project.update');
    Route::delete('/project/destroy/{id}', 'ProjectController@destroy')->name('project.destroy')->middleware('permission:project.destroy');
    Route::get('/project/settings/{id}', 'ProjectController@showsetting')->name('project.showsetting')->middleware('permission:project.showsetting');
    Route::post('/project/store/setting', 'ProjectController@storesetting')->name('project.storesetting')->middleware('permission:project.store.setting');
    
    Route::get('/project/folder/create', 'ProjectController@create_folder')->name('project.folder.create');
    Route::post('/folder/store', 'ProjectController@store_folder')->name('project.folder.store'); 
    Route::delete('folder/destroy/{id}', 'ProjectController@destroy_folder')->name('project.folder.destroy')->middleware('permission:admin.project.destroy');
    
    /**  
     * Device
     */
    Route::get('project/device/list/{id}', 'DeviceController@show')->name('project.device.show');
    Route::get('project/device/create/{id}', 'DeviceController@create')->name('project.device.create');
    Route::post('project/device/store', 'DeviceController@store')->name('project.device.store');
    Route::get('project/device/edit/{id}', 'DeviceController@edit')->name('project.device.edit');
    Route::post('project/device/update/{id}', 'DeviceController@update')->name('project.device.update');
    Route::delete('project/device/destroy/{id}', 'DeviceController@destroy')->name('project.device.destroy');

    Route::get('project/device', 'DeviceController@index')->name('device.index')->middleware('permission:device.index');
    Route::get('project/category/create/{device}', 'DeviceController@create_category')->name('category.create')->middleware('permission:device.create');
       
     
    Route::get('project/feature/show/{id}', 'ProjectFeatureController@show')->name('project.feature.show');//->middleware('permission:device.index');
   
  
    /**
     * Project Feature
     */
    Route::get('project/feature/create/{id}', 'ProjectFeatureController@create')->name('project.feature.create');
    Route::post('project/feature/store', 'ProjectFeatureController@store')->name('project.feature.store');
 
    /**
     * Asign Folder 
     */
    Route::post('project/folder/store', 'AsignProjectController@store_assignfolder')->name('asign.folder.store');

    /** 
     * Asign Project
     */
    Route::resource('asignproject', 'AsignProjectController')->middleware('permission:asignproject.index');
    Route::get('project/device/feature/assign/{id}', 'AsignProjectController@asign_device_feature')->name('project.device.feature.asign');
    Route::post('project/device/feature/store', 'AsignProjectController@store_device_feature')->name('project.device.feature.store');
    
    /** 
     * Asign User
     */
    Route::resource('asignuser', 'AsignUserController')->middleware('permission:asignproject.index');

    
    /**
     * Asign Device
     */
    Route::resource('asigndevice', 'AsignDeviceController')->middleware('permission:asigndevice.index');

     /**
     * CommonCode
     */
    Route::get('common-codes', [CommonCodeController::class,'index'])->name('common-codes.index');
    Route::get('common-codes/update-order', [CommonCodeController::class,'updateOrder'])->name('common-codes.update-order');
    Route::get('common-codes/create', [CommonCodeController::class,'create'])->name('common-codes.create');
    Route::post('common-codes/store', [CommonCodeController::class,'store'])->name('common-codes.store');
    Route::get('common-codes/show/{id}', [CommonCodeController::class,'show'])->name('common-codes.show');
    Route::get('common-codes/edit/{id}', [CommonCodeController::class,'edit'])->name('common-codes.edit');
    Route::put('common-codes/update/{id}', [CommonCodeController::class,'update'])->name('common-codes.update');
    Route::delete('common-codes/destroy/{id}', [CommonCodeController::class,'destroy'])->name('common-codes.destroy');
 
    /**
     * Folder
     */
    Route::resource('folder', 'FolderController');

    /**
     * User Management
     */
    Route::resource('users', 'Users\UsersController')
        ->except('update')->middleware('permission:users.manage');

    Route::group(['prefix' => 'users/{user}', 'middleware' => 'permission:users.manage'], function () {
        Route::put('update/details', 'Users\DetailsController@update')->name('users.update.details');
        Route::put('update/login-details', 'Users\LoginDetailsController@update')
            ->name('users.update.login-details');

        Route::post('update/avatar', 'Users\AvatarController@update')->name('user.update.avatar');
        Route::post('update/avatar/external', 'Users\AvatarController@updateExternal')
            ->name('user.update.avatar.external');

        Route::get('sessions', 'Users\SessionsController@index')
            ->name('user.sessions')->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'Users\SessionsController@destroy')
            ->name('user.sessions.invalidate')->middleware('session.database');

        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('user.two-factor.enable');
        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('user.two-factor.disable');
    });

    /**
     * Roles & Permissions
     */
    Route::group(['namespace' => 'Authorization'], function () {
        Route::resource('roles', 'RolesController')->except('show')->middleware('permission:roles.manage');

        Route::post('permissions/save', 'RolePermissionsController@update')
            ->name('permissions.save')
            ->middleware('permission:permissions.manage');

        Route::resource('permissions', 'PermissionsController')->middleware('permission:permissions.manage');
    });

    /**
     * Settings
     */

    Route::get('settings', 'SettingsController@general')->name('settings.general')
        ->middleware('permission:settings.general');

    Route::post('settings/general', 'SettingsController@update')->name('settings.general.update')
        ->middleware('permission:settings.general');

    Route::get('settings/auth', 'SettingsController@auth')->name('settings.auth')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth', 'SettingsController@update')->name('settings.auth.update')
        ->middleware('permission:settings.auth');

    if (config('services.authy.key')) {
        Route::post('settings/auth/2fa/enable', 'SettingsController@enableTwoFactor')
            ->name('settings.auth.2fa.enable')
            ->middleware('permission:settings.auth');

        Route::post('settings/auth/2fa/disable', 'SettingsController@disableTwoFactor')
            ->name('settings.auth.2fa.disable')
            ->middleware('permission:settings.auth');
    }

    Route::post('settings/auth/registration/captcha/enable', 'SettingsController@enableCaptcha')
        ->name('settings.registration.captcha.enable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/registration/captcha/disable', 'SettingsController@disableCaptcha')
        ->name('settings.registration.captcha.disable')
        ->middleware('permission:settings.auth');

    Route::get('settings/notifications', 'SettingsController@notifications')
        ->name('settings.notifications')
        ->middleware('permission:settings.notifications');

    Route::post('settings/notifications', 'SettingsController@update')
        ->name('settings.notifications.update')
        ->middleware('permission:settings.notifications');

    /**
     * Activity Log
     */

    Route::get('activity', 'ActivityController@index')->name('activity.index')
        ->middleware('permission:users.activity');

    Route::get('activity/user/{user}/log', 'Users\ActivityController@index')->name('activity.user')
        ->middleware('permission:users.activity');
});


/**
 * Installation
 */

Route::group(['prefix' => 'install'], function () {
    Route::get('/', 'InstallController@index')->name('install.start');
    Route::get('requirements', 'InstallController@requirements')->name('install.requirements');
    Route::get('permissions', 'InstallController@permissions')->name('install.permissions');
    Route::get('database', 'InstallController@databaseInfo')->name('install.database');
    Route::get('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('install-app', 'InstallController@install')->name('install.install');
    Route::get('complete', 'InstallController@complete')->name('install.complete');
    Route::get('error', 'InstallController@error')->name('install.error');
});
