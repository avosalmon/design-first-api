<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

beforeEach(fn () => useSchema('openapi.yaml'));

it('returns paginated response', function () {
    $perPage = 10;
    $pages = 3;
    $page = 2;

    $user = User::factory()->create();
    Post::factory()
        ->for($user, 'author')
        ->count($perPage * $pages)
        ->create();

    Sanctum::actingAs($user, ['*']);

    getJson("users/{$user->id}/posts?per_page={$perPage}&page={$page}&sort_by=id&sort_order=desc")
        ->assertOk()
        ->assertSchema()
        ->assertJson(
            fn (AssertableJson $json) => $json->has('data', $perPage)
                ->has('links')
                ->has('meta.links')
                ->where('meta.current_page', $page)
                ->where('meta.last_page', $pages)
                ->where('meta.from', $perPage * ($page - 1) + 1)
                ->where('meta.to', $perPage * $page)
                ->where('meta.total', $perPage * $pages)
        );
});

it('returns a 401 when user is not authenticated', function () {
    getJson('users/1/posts')
        ->assertUnauthorized()
        ->assertSchema();
});

it('returns a 403 when user is not authorized', function () {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    getJson("users/{$targetUser->id}/posts")
        ->assertForbidden()
        ->assertSchema();
});

it('returns a 404 when user is not found', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    getJson('users/999/posts')
        ->assertNotFound()
        ->assertSchema();
});

it('returns a 422 when invalid parameters are provided', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    getJson("users/{$user->id}/posts?per_page=wrong&page=wrong&sort_order=wrong&sort_by=wrong")
        ->assertUnprocessable()
        ->assertSchema()
        ->assertInvalid([
            'page' => [
                'The page must be an integer.',
            ],
            'per_page' => [
                'The per page must be an integer.',
                'The selected per page is invalid.',
            ],
            'sort_order' => [
                'The selected sort order is invalid.',
            ],
            'sort_by' => [
                'The selected sort by is invalid.',
            ],
        ]);
});
