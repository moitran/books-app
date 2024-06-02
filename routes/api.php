<?php

use App\Http\Controllers\BookController;
use App\Http\Middleware\CacheMiddleware;
use App\Http\Middleware\CheckCacheMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/books/search', [BookController::class, 'search'])->name('books.search')->middleware([CheckCacheMiddleware::class, CacheMiddleware::class]);
Route::resource('/books', BookController::class);
