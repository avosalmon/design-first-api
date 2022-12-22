<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

beforeEach(fn () => useSchema('openapi.yaml'));

it('returns a 401 when user is not authenticated', function () {
    postJson('posts', [
        'title' => 'My First Post',
        'slug' => 'my-first-post',
        'content' => 'This is my first post.',
    ])
        ->assertSchema()
        ->assertUnauthorized();
});

it('returns a 422 when invalid parameters are provided', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    postJson('posts', [
        'title' => 1,
        'slug' => 123,
    ])
        ->assertSchema()
        ->assertUnprocessable()
        ->assertInvalid([
            'title',
            'slug',
            'content',
        ]);
});
