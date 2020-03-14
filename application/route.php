<?php

use  think\Route;
Route::rule('login','index/user/login');
Route::rule('register','index/user/register');
Route::rule('user','index/user/index');
Route::rule('logout','index/user/logout');
Route::rule('detail','index/index/detail');
Route::rule('create','index/index/create');