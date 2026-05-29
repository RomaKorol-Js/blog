<?php

use App\Http\Controllers\Api\Blog\Admin\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([ 'namespace' => 'App\Http\Controllers\Api\Blog', 'prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

$groupData = [
    'namespace' => 'App\Http\Controllers\Api\Blog\Admin',
    'prefix' => 'admin/blog',
];
Route::group($groupData, function () {
    $methods = ['index','store','update'];
    Route::apiResource('categories', CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');
 
//BlogPost
Route::apiResource('posts', PostController::class)
    ->except(['show'])                               //не робити маршрут для метода show
    ->names('blog.admin.posts');
 });