<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexUserPostRequest;
use App\Http\Resources\PostCollection;
use App\Models\User;

class UserPostController extends Controller
{
    public function index(User $user, IndexUserPostRequest $request): PostCollection
    {
        $perPage = $request->integer('per_page', 10);
        $sortBy = $request->string('sort_by', 'id');
        $sortOrder = $request->string('sort_order', 'desc');

        $posts = $user->posts()
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        return new PostCollection($posts);
    }
}
