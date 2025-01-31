<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DenemeController;

Route::get('/', function () {
    return view('apidoc');
});