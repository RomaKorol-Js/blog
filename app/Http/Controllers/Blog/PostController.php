<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Api\Blog\PostController as ApiPostController;

class PostController extends ApiPostController
{
    // Proxy controller so the existing route group stays unchanged.
}
