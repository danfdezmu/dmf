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
            ->has('companyWebsites')
            ->has('projects', 1),
        );
});

test('home page shows unique company website previews', function () {
    SiteSetting::query()->create(SiteSetting::defaults());

    Experience::factory()->create([
        'company' => 'Empresa A',
        'website_url' => 'https://empresa-a.test',
        'link_preview' => [
            'title' => 'Empresa A',
            'description' => 'Descripcion',
            'image' => null,
            'url' => 'https://empresa-a.test',
            'domain' => 'empresa-a.test',
        ],
        'is_published' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('companyWebsites', 1)
            ->where('companyWebsites.0.link_preview.title', 'Empresa A'),
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
