<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('creates a post', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    postJson('posts', [
        'title' => 'My First Post',
        'slug' => 'my-first-post',
        'content' => 'This is my first post.',
    ])
        ->assertCreated()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data.user_id', $user->id)
                ->where('data.title', 'My First Post')
                ->where('data.slug', 'my-first-post')
                ->where('data.content', 'This is my first post.')
                ->etc()
        );

    assertDatabaseHas('posts', [
        'user_id' => $user->id,
        'title' => 'My First Post',
        'slug' => 'my-first-post',
        'content' => 'This is my first post.',
    ]);
});

it('returns a 401 when user is not authenticated', function () {
    postJson('posts', [
        'title' => 'My First Post',
        'slug' => 'my-first-post',
        'content' => 'This is my first post.',
    ])->assertUnauthorized();
});

it('returns a 422 when invalid parameters are provided', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    postJson('posts', [
        'title' => 1,
        'slug' => 123,
    ])->assertUnprocessable()
        ->assertInvalid([
            'title',
            'slug',
            'content',
        ]);
});
