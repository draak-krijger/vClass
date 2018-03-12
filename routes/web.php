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

// Auth::routes();

    // Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// routes start 
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/addTeacher', 'HomeController@TeacherAddPage')->name('TeacherAdd');
Route::post('/addTeacher', 'HomeController@TeacherAdd')->name('postTeacherAdd');
Route::post('/addCourse', 'HomeController@addCourse')->name('addCourse');
Route::get('/teacher/{id}' , 'HomeController@TeacherHomeOther')->name('ThomeOther');

Route::get('/course/{id}' , 'HomeController@showCourse')->name('showCourse');
Route::post('/submitAttendence' , 'HomeController@submitAttendance') ;

Route::post('/addNewStudent' , 'HomeController@AddNewStudent');
Route::post('/closeCourse' , 'HomeController@CourseClose');
Route::post('/postInfo' , 'HomeController@postInfo');
Route::post('/postAssignment' , 'HomeController@postAssignment');
Route::post('/postResult' , 'HomeController@postResult');
Route::post('/addNewKey' , 'HomeController@addNewKey');

Route::get('/assignment/{id}' , 'HomeController@showAssignment');
Route::post('/submitAssignment' , 'HomeController@submitAssignment');
Route::post('/closeAssignment' , 'HomeController@closeAssignment');
Route::get('/download/{id1}/{id2}' , 'HomeController@downloadFile');

Route::get('/ajaxtest' , 'HomeController@getAjax');
Route::post('/ajaxtest' , 'HomeController@postAjax');
