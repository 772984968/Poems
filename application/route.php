<?php

use  think\Route;
Route::rule('login','index/user/login');
Route::rule('register','index/user/register');
Route::rule('user','index/user/index');
Route::rule('logout','index/user/logout');
Route::rule('detail','index/index/detail');
Route::rule('create','index/index/create');
Route::rule('manage','index/manage/index');
Route::rule('update','index/manage/update');
Route::rule('delete','index/manage/delete');
Route::rule('collect','index/user/collect');
Route::rule('like','index/user/like');
Route::rule('mycollect','index/user/mycollect');
Route::rule('delcollect','index/user/delcollect');
Route::rule('mylike','index/user/mylike');
Route::rule('dellike','index/user/dellike');
Route::rule('comment','index/user/comment');
Route::rule('comments','index/user/comments');
Route::rule('infocomments','index/user/infocomments');