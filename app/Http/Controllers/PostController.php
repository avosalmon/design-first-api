<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\Post as PostResource;

class PostController extends Controller
{
    public function store(StorePostRequest $request): PostResource
    {
        // ...
    }
}
