<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

include('backend/ui.php');

Route::group(
    [
        'prefix'     => 'backend/json',
        'middleware' => ['web'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::any( '/assets', 'JsonController@getPublicAssets' )
            ->name( 'vh.backend.json.assets' );
        //------------------------------------------------
        Route::any( '/is-logged-in', 'JsonController@isLoggedIn' )
            ->name( 'vh.backend.json.is_logged_in' );
        //------------------------------------------------
        //------------------------------------------------
    });


Route::group(
    [
        'prefix'     => 'backend',
        'middleware' => ['web'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers\Frontend'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::any( '/signin/post', 'PublicController@postLogin' )
            ->name( 'vh.backend.signin.post' );
        //------------------------------------------------

        //------------------------------------------------
        //------------------------------------------------
    });


Route::group(
    [
        'prefix' => 'backend/vaah/registrations',
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::any('/assets', 'RegistrationsController@getAssets')
            ->name('backend.vaah.registrations.assets');
        //---------------------------------------------------------
        Route::post('/create', 'RegistrationsController@postCreate')
            ->name('backend.vaah.registrations.create');
        //---------------------------------------------------------
        Route::post('/list', 'RegistrationsController@getList')
            ->name('backend.vaah.registrations.list');
        //---------------------------------------------------------
        Route::any('/item/{id}', 'RegistrationsController@getItem')
            ->name('backend.vaah.registrations.item');
        //---------------------------------------------------------
        Route::post('/store/{id}', 'RegistrationsController@postStore')
            ->name('backend.vaah.registrations.store');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'RegistrationsController@postActions')
            ->name('backend.vaah.registrations.actions');
        //---------------------------------------------------------
    });





/*Route::group(
    [
        'prefix'     => 'backend/vaah/registrations',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        Route::get( '/', 'RegistrationController@index' )
            ->name( 'vh.backend.registrations' );
        //------------------------------------------------
        Route::any( '/assets', 'RegistrationController@assets' )
            ->name( 'vh.backend.registrations.assets' );
        //------------------------------------------------
        Route::any( '/list', 'RegistrationController@getList' )
            ->name( 'vh.backend.registrations.list' );
        //------------------------------------------------
        Route::any( '/actions', 'RegistrationController@actions' )
            ->name( 'vh.backend.registrations.actions' );
        //------------------------------------------------
        Route::any( '/store', 'RegistrationController@store' )
            ->name( 'vh.backend.registrations.store' );
        //------------------------------------------------
        Route::any( '/view/{id}', 'RegistrationController@getDetails' )
            ->name( 'vh.backend.registrations.view' );
        //------------------------------------------------
    });*/



//=================OLD ROUTES===========================================





include('backend/settings.php');

Route::group(
    [
        'prefix'     => 'vaahcms/setup',
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers\Frontend'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/', 'SetupController@index' )
            ->name( 'vh.setup.index' );
        //------------------------------------------------
        Route::post( '/check/status', 'SetupController@checkSetupStatus' )
            ->name( 'vh.setup.check.status' );
        //------------------------------------------------
        Route::post( '/store/app/info', 'SetupController@storeAppInfo' )
            ->name( 'vh.setup.store.app.info' );
        //------------------------------------------------
        Route::post( '/run/migrations', 'SetupController@runMigrations' )
            ->name( 'vh.setup.run.migrations' );
        //------------------------------------------------
        Route::any( '/setup/cms', 'SetupController@setupCMS' )
            ->name( 'vh.setup.run.migrations' );
        //------------------------------------------------
        Route::post( '/store/admin', 'SetupController@storeAdmin' )
            ->name( 'vh.setup.store.backend' );
        //------------------------------------------------
        //------------------------------------------------
    });



Route::group(
    [
        'prefix'     => 'backend',
        'middleware' => ['web'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers\Frontend'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/', 'PublicController@login' )
            ->name( 'vh.backend' );
        //------------------------------------------------
        Route::get( '/login', 'PublicController@redirectToLogin' )
            ->name( 'vh.backend.login' );
        //------------------------------------------------
        Route::post( '/login/post', 'PublicController@postLogin' )
            ->name( 'vh.backend.login.post' );
        //------------------------------------------------
        Route::get( '/logout', 'PublicController@logout' )
            ->name( 'vh.backend.logout' );
        //------------------------------------------------
        //------------------------------------------------
    });



Route::group(
    [
        'prefix'     => 'admin',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/dashboard', 'DashboardController@index' )
            ->name( 'vh.backend.dashboard' );
        //------------------------------------------------
        Route::get( '/layout/app', 'DashboardController@layoutApp' )
            ->name( 'vh.backend.layout.app' );
        //------------------------------------------------
        //------------------------------------------------
        //------------------------------------------------
    });





Route::group(
    [
        'prefix'     => 'admin/vaah',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        Route::get( '/', 'DashboardController@vaah' )
            ->name( 'vh.backend.vaah' );
        //------------------------------------------------
        //------------------------------------------------
        //------------------------------------------------
    });






Route::group(
    [
        'prefix'     => 'admin/vaah/modules',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/', 'ModuleController@index' )
            ->name( 'vh.backend.modules' );
        //------------------------------------------------
        Route::any( '/assets', 'ModuleController@assets' )
            ->name( 'vh.backend.modules.assets' );
        //------------------------------------------------
        Route::any( '/download', 'ModuleController@download' )
            ->name( 'vh.backend.modules.download' );
        //------------------------------------------------
        Route::any( '/list', 'ModuleController@getList' )
            ->name( 'vh.backend.modules.list' );
        //------------------------------------------------
        Route::any( '/actions', 'ModuleController@actions' )
            ->name( 'vh.backend.modules.actions' );
        //------------------------------------------------
        Route::any( '/get/slugs', 'ModuleController@getModulesSlugs' )
            ->name( 'vh.backend.modules.get.slugs' );
        //------------------------------------------------
        Route::any( '/update/versions', 'ModuleController@updateModuleVersions' )
            ->name( 'vh.backend.modules.update.version' );
        //------------------------------------------------
        Route::any( '/install/updates', 'ModuleController@installUpdates' )
            ->name( 'vh.backend.modules.install.updates' );
        //------------------------------------------------
        //------------------------------------------------
        //------------------------------------------------
    });


Route::group(
    [
        'prefix'     => 'admin/vaah/themes',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/', 'ThemeController@index' )
            ->name( 'vh.backend.themes' );
        //------------------------------------------------
        Route::any( '/assets', 'ThemeController@assets' )
            ->name( 'vh.backend.themes.assets' );
        //------------------------------------------------
        Route::any( '/download', 'ThemeController@download' )
            ->name( 'vh.backend.themes.download' );
        //------------------------------------------------
        Route::any( '/list', 'ThemeController@getList' )
            ->name( 'vh.backend.themes.list' );
        //------------------------------------------------
        Route::any( '/actions', 'ThemeController@actions' )
            ->name( 'vh.backend.themes.actions' );
        //------------------------------------------------
        Route::any( '/get/slugs', 'ThemeController@getModulesSlugs' )
            ->name( 'vh.backend.themes.get.slugs' );
        //------------------------------------------------
        Route::any( '/update/versions', 'ThemeController@updateModuleVersions' )
            ->name( 'vh.backend.themes.update.version' );
        //------------------------------------------------
        Route::any( '/install/updates', 'ThemeController@installUpdates' )
            ->name( 'vh.backend.themes.install.updates' );
        //------------------------------------------------
        //------------------------------------------------
        //------------------------------------------------
    });



Route::group(
    [
        'prefix'     => 'admin/composer',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        Route::get( '/install', 'ComposerController@install' )
            ->name( 'vh.backend.composer.install' );
        //------------------------------------------------
        //------------------------------------------------
    });




Route::group(
    [
        'prefix'     => 'admin/vaah/users',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        Route::get( '/', 'UserController@index' )
            ->name( 'vh.backend.users' );
        //------------------------------------------------
        Route::any( '/assets', 'UserController@assets' )
            ->name( 'vh.backend.users.assets' );
        //------------------------------------------------
        Route::any( '/list', 'UserController@getList' )
            ->name( 'vh.backend.users.list' );
        //------------------------------------------------
        Route::any( '/actions', 'UserController@actions' )
            ->name( 'vh.backend.users.actions' );
        //------------------------------------------------
        Route::any( '/store', 'UserController@store' )
            ->name( 'vh.backend.users.store' );
        //------------------------------------------------
        Route::any( '/view/{id}', 'UserController@getDetails' )
            ->name( 'vh.backend.users.view' );
        //------------------------------------------------
        Route::any( '/roles/{id}', 'UserController@getRoles' )
            ->name( 'vh.backend.users.roles' );
        //------------------------------------------------
    });

Route::group(
    [
        'prefix'     => 'backend/vaah/roles',
        'middleware' => ['web','has.backend.access'],
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers'
    ],
    function () {
        //------------------------------------------------
        Route::get( '/', 'RolesController@index' )
            ->name( 'vh.backend.roles' );
        //------------------------------------------------
        Route::any( '/assets', 'RolesController@getAssets' )
            ->name( 'vh.backend.roles.assets' );
        //------------------------------------------------
        Route::any( '/list', 'RolesController@getList' )
            ->name( 'vh.backend.roles.list' );
        //------------------------------------------------
        Route::any('/item/{id}', 'RolesController@getItem')
            ->name('backend.vaah.roles.item');
        //---------------------------------------------------------
        Route::any('/role/{id}', 'RolesController@getRoles')
            ->name('backend.vaah.roles.role');
        //---------------------------------------------------------
        Route::post('/store/{id}', 'RolesController@postStore')
            ->name('backend.vaah.roles.store');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'RolesController@postActions')
            ->name('backend.vaah.roles.actions');
        //---------------------------------------------------------
        //------------------------------------------------
    });

Route::group(
    [
        'prefix' => 'backend/vaah/permissions',
        'namespace'  => 'WebReinvent\VaahCms\Http\Controllers',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::any('/assets', 'PermissionsController@getAssets')
            ->name('backend.vaah.permissions.assets');
        //---------------------------------------------------------
        Route::any('/list', 'PermissionsController@getList')
            ->name('backend.vaah.permissions.list');
        //---------------------------------------------------------
        Route::any('/item/{id}', 'PermissionsController@getItem')
            ->name('backend.vaah.permissions.item');
        //---------------------------------------------------------
        Route::any('/role/{id}', 'PermissionsController@getRoles')
            ->name('backend.vaah.permissions.role');
        //---------------------------------------------------------
        Route::post('/store/{id}', 'PermissionsController@postStore')
            ->name('backend.vaah.permissions.store');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'PermissionsController@postActions')
            ->name('backend.vaah.permissions.actions');
        //---------------------------------------------------------
        Route::post('/getModuleSections', 'PermissionsController@getModuleSections')
            ->name('backend.vaah.permissions.module-section');
        //---------------------------------------------------------
    });
