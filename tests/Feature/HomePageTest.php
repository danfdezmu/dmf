<?php

use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('home page shows portfolio content from database', function () {
    $settings = SiteSetting::query()->create(SiteSetting::defaults());

    Project::factory()->create([
        'title' => 'Proyecto Demo',
        'is_published' => true,
    ]);

    Experience::factory()->create([
        'company' => 'Empresa Demo',
        'is_published' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->where('profile.name', $settings->name)
            ->has('services', 3)
            ->has('experiences', 1)
            ->has('education')
            ->has('projects', 1),
        );
});

test('unpublished projects are hidden on home page', function () {
    SiteSetting::query()->create(SiteSetting::defaults());

    Project::factory()->create(['is_published' => false]);
    Project::factory()->create(['is_published' => true, 'title' => 'Visible']);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('projects', 1)
            ->where('projects.0.title', 'Visible'),
        );
});
