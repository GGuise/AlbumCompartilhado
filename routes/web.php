<?php

use App\Mail\ContactForm;

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

Route::get('/', 'FrontendController@home')->name('homepage');
Route::get('/gallery/{slug}', 'FrontendController@gallery')->name('gallery');

// Rotas públicas para Álbuns Compartilhados
Route::get('/shared/{slug}', 'FrontendController@shared_gallery')->name('shared_gallery');
Route::get('/shared/{slug}/upload', 'FrontendController@shared_upload_view')->name('shared_upload_view');
Route::post('/shared/{slug}/upload', 'FrontendController@shared_upload_post')->name('shared_upload_post');
Route::post('/shared/{slug}/upload-ajax', 'FrontendController@shared_upload_ajax')->name('shared_upload_ajax');

Route::get('/about', 'FrontendController@about')->name('about');
Route::get('/contact', 'FrontendController@contact')->name('contact');
Route::post('/contact', 'FrontendController@contactForm');
Route::get('/service', 'FrontendController@service')->name('service');
Route::get('/mapa-do-site', 'FrontendController@visualSitemap')->name('visual_sitemap');
Route::get('/sitemap.xml', 'FrontendController@sitemap')->name('sitemap');

Route::get('/mail', function(){
    
    $data = [
        "fname" => "Tawhidul Islam",
        "lname" => "Khan",
        "email" => "tawhid@gmail.com",
        "subject" => "Contact",
        "message" => "Hello Tawhid",
    ];
    return new ContactForm($data);
});

/* 
| ==========================
|  Authenticate Routes
| ==========================
*/


Route::get('/register','AuthController@resgisterShow')->name('registerr');
Route::post('/register','AuthController@resgisterStore');
Route::get('/login','AuthController@loginShow')->name('login');
Route::post('/login','AuthController@loginStore');
Route::post('/logout','AuthController@logout')->name('logout');
Route::get('/verify/{token}','AuthController@verify')->name('verify');
Route::get('/verify-again','AuthController@verifyAgain')->name('verifyAgain');
Route::post('/verify-again','AuthController@resendVerification');

/* 
| ==========================
|  Password Reset Routes
| ==========================
*/

Route::get('/password/reset','AuthController@passwordResetToken')->name('passwordResetToken');
Route::post('/password/reset','AuthController@passwordResetTokenSend');

Route::get('/password/reset/update/{token?}','AuthController@passwordReset')->name('passwordReset');
Route::post('/password/reset/update','AuthController@passwordResetUpdate');




Route::group(['prefix'=>'/admin'],function(){

    Route::delete('/album/bulk-destroy', 'AlbumController@bulkDestroy')->name('album.bulk_destroy');
    Route::post('/album/bulk-toggle-uploads', 'AlbumController@bulkToggleUploads')->name('album.bulk_toggle_uploads');
    Route::resource('/album','AlbumController');
    Route::resource('/meu-album-compartilhado','MeuAlbumCompartilhadoController');
    Route::post('/meu-album-compartilhado/{album}/fotos', 'MeuAlbumCompartilhadoController@uploadFotos')->name('meu-album-compartilhado.upload');
    Route::post('/meu-album-compartilhado/{album}/upload-ajax', 'MeuAlbumCompartilhadoController@uploadFotosAjax')->name('meu-album-compartilhado.upload_ajax');
    Route::post('/meu-album-compartilhado/bulk-toggle-uploads', 'MeuAlbumCompartilhadoController@bulkToggleUploads')->name('meu-album-compartilhado.bulk_toggle_uploads');
    Route::delete('/meu-album-compartilhado/bulk-destroy', 'MeuAlbumCompartilhadoController@bulkDestroy')->name('meu-album-compartilhado.bulk_destroy');
    Route::delete('/meu-album-compartilhado/foto/{foto}', 'MeuAlbumCompartilhadoController@destroyFoto')->name('meu-album-compartilhado.foto.destroy');
    Route::delete('/photo/bulk-destroy', 'PhotoController@bulkDestroy')->name('photo.bulk_destroy');
    Route::resource('/photo','PhotoController');
    Route::delete('/team/bulk-destroy', 'TeamController@bulkDestroy')->name('team.bulk_destroy');
    Route::resource('/team','TeamController');
    Route::resource('/settings','SettingController');
    Route::resource('/service','ServiceController');
    Route::resource('/contactinfo','ContactInfoController');
    Route::resource('/permission','PermissionController');
    Route::resource('/role','RoleController');

    /*=================
      | User Settings 
    ====================*/

    Route::resource('/user','UserController');
    Route::get('/profile','UserController@profile')->name('user.profile');
    Route::put('/profile','UserController@profile_update')->name('profile.update');

    // Google Drive Authorization
    Route::get('/google-drive/auth', 'GoogleDriveAuthController@redirect')->name('google.drive.auth');
    Route::get('/google-drive/callback', 'GoogleDriveAuthController@callback')->name('google.drive.callback');

});

Route::get('/admin', 'HomeController@index')->name('dashboard');

// Google Socialite Login Routes
Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle')->name('google.login');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

Route::get('/{token}/{id}', 'UserController@newUserPassSet')->name('new.user');

// Route::group(['prefix'=>'/category'],function(){

//     Route::get('/{slug}','CategoryController@catshow')->name('catshow');

// });
