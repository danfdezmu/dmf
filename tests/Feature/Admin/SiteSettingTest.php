<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests cannot access site settings', function () {
    $this->get(route('admin.site.edit'))->assertRedirect(route('login'));
});

test('authenticated users can update site settings', function () {
    SiteSetting::query()->create(SiteSetting::defaults());

    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.site.update'), [
            'brand_label' => 'DMF',
            'name' => 'Dan Haziel',
            'role' => 'Full Stack Developer',
            'intro' => 'Nueva introduccion.',
            'linkedin_url' => 'https://www.linkedin.com/in/example',
            'contact_email' => 'contacto@example.com',
            'work_steps_text' => "Paso uno\nPaso dos",
            'services' => [
                ['title' => 'Web', 'description' => 'Sitios web'],
            ],
            'education' => [
                [
                    'institution' => 'Universidad Ejemplo',
                    'degree' => 'Licenciatura',
                    'field' => 'Informatica',
                ],
            ],
        ])
        ->assertRedirect(route('admin.site.edit'));

    $settings = SiteSetting::query()->first();

    expect($settings)->not->toBeNull()
        ->and($settings->name)->toBe('Dan Haziel')
        ->and($settings->work_steps)->toBe(['Paso uno', 'Paso dos']);
});

test('authenticated users can view site settings form', function () {
    SiteSetting::query()->create(SiteSetting::defaults());

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.site.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/SiteSettings')
            ->has('settings'),
        );
});
