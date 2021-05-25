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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mostrar', function () {
    $title = "Show date";
    return view('show', array(
        'title' => $title
    ));
});

Route::get('/movie/{title}/{year}', function ($title = 'Without movies', $year) {
    return view('movie', array(
        "title" => $title,
        "year" => $year
    ));
})->where(array(
    'title' => '[a-z]+',
    'year' => '[0-9]+'
));