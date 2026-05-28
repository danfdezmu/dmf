<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests cannot manage projects', function () {
    $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
});

test('authenticated users can create projects', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [
            'title' => 'Nuevo MVP',
            'description' => 'Demo para cliente.',
            'stack' => 'Laravel, Vue',
            'url' => null,
            'sort_order' => 1,
            'is_published' => true,
        ])
        ->assertRedirect(route('admin.projects.index'));

    expect(Project::query()->where('title', 'Nuevo MVP')->exists())->toBeTrue();
});

test('authenticated users can delete projects', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.projects.destroy', $project))
        ->assertRedirect(route('admin.projects.index'));

    expect(Project::query()->find($project->id))->toBeNull();
});
