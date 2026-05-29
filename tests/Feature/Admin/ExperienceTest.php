<?php

use App\Models\Experience;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests cannot manage experiences', function () {
    $this->get(route('admin.experiences.index'))->assertRedirect(route('login'));
});

test('authenticated users can create experiences', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.experiences.store'), [
            'company' => 'Acme Corp',
            'role' => 'Desarrollador Laravel',
            'location' => 'Ciudad de Mexico',
            'started_at' => '2023-01-01',
            'ended_at' => null,
            'description' => 'Desarrollo de APIs y paneles admin.',
            'sort_order' => 1,
            'is_published' => true,
        ])
        ->assertRedirect(route('admin.experiences.index'));

    expect(Experience::query()->where('company', 'Acme Corp')->exists())->toBeTrue();
});

test('experiences expose resolved logo url on home page', function () {
    SiteSetting::query()->create(SiteSetting::defaults());

    Experience::factory()->create([
        'company' => 'Empresa con logo',
        'logo_url' => '/images/logos/test.svg',
        'is_published' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('experiences.0.logo', '/images/logos/test.svg'),
        );
});

test('uploaded experience logos resolve to a public storage path', function () {
    Storage::fake('public');

    $experience = Experience::factory()->create([
        'logo_url' => 'experience-logos/acme.png',
    ]);

    Storage::disk('public')->put('experience-logos/acme.png', 'fake-image');

    expect($experience->fresh()->logo)->toBe('/storage/experience-logos/acme.png');
});

test('authenticated users can upload a logo when updating an experience', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $experience = Experience::factory()->create([
        'logo_url' => '/images/logos/old.svg',
    ]);

    $payload = [
        '_method' => 'patch',
        'company' => $experience->company,
        'role' => $experience->role,
        'location' => $experience->location,
        'started_at' => $experience->started_at->format('Y-m-d'),
        'ended_at' => $experience->ended_at?->format('Y-m-d'),
        'description' => $experience->description,
        'sort_order' => $experience->sort_order,
        'is_published' => $experience->is_published,
        'website_url' => $experience->website_url,
        'logo' => UploadedFile::fake()->image('logo.png'),
    ];

    $this->actingAs($user)
        ->post(route('admin.experiences.update', $experience), $payload)
        ->assertRedirect(route('admin.experiences.index'));

    $experience->refresh();

    expect($experience->logo_url)->toStartWith('experience-logos/')
        ->and($experience->logo)->toBe('/storage/'.$experience->logo_url)
        ->and(Storage::disk('public')->exists($experience->logo_url))->toBeTrue();
});

test('authenticated users can delete experiences', function () {
    $user = User::factory()->create();
    $experience = Experience::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.experiences.destroy', $experience))
        ->assertRedirect(route('admin.experiences.index'));

    expect(Experience::query()->find($experience->id))->toBeNull();
});
