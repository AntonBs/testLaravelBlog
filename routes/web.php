<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'BlogPostController@index');


  //Это может заменить все роутинги ниже
Route::resource('/post', 'BlogPostController');

//Route::get('/', 'BlogPostController@index');
//Route::get('post/', 'BlogPostController@index')->name('post.index');
//Route::get('post/create', 'BlogPostController@create')->name('post.create');
//Route::get('post/show/{id}', 'BlogPostController@show')->name('post.show');
//Route::get('post/edit/{id}', 'BlogPostController@edit')->name('post.edit');
//Route::post('post/', 'BlogPostController@store')->name('post.store');
//Route::patch('post/show/{id}', 'BlogPostController@update')->name('post.update');
//Route::delete('post/{id}', 'BlogPostController@destroy')->name('post.destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

