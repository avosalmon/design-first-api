<?php

beforeEach(fn () => useSchema('openapi.yaml'));

it('creates a post');

it('returns a 401 when user is not authenticated');

it('returns a 422 when invalid parameters are provided');
