<?php

use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

test('authenticated users can delete experiences', function () {
    $user = User::factory()->create();
    $experience = Experience::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.experiences.destroy', $experience))
        ->assertRedirect(route('admin.experiences.index'));

    expect(Experience::query()->find($experience->id))->toBeNull();
});
