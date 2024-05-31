<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::resource('/books', BookController::class);
