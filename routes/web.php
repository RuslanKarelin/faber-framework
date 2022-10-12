<?php

use App\Controllers\HomeController;
use Faber\Core\Request\Request;
use Faber\Core\Facades\Route;
use \Faber\Core\Facades\Auth;

Route::get('/', [HomeController::class, 'index']);

Route::prefix('admin')->middleware(['auth'])->group(function (Request $request) {});

Auth::routes();