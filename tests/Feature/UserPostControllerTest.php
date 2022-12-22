<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(fn () => useSchema('openapi.yaml'));

it('returns paginated response');

it('returns a 401 when user is not authenticated');

it('returns a 403 when user is not authorized');

it('returns a 422 when invalid parameters are provided');
