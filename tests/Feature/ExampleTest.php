<?php

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('returns a successful response', function () {
    SiteSetting::query()->create(SiteSetting::defaults());

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->has('profile')
            ->has('services')
            ->has('experiences')
            ->has('education')
            ->has('companyWebsites')
            ->where('auth.user', null),
        );
});
