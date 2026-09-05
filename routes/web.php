<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/blog', function () {
    return view('blog');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/kontak', function () {
    return redirect('/contact');
});

Route::get('/about', function () {
    return view('about');
});