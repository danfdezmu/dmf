<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Projects/Index', [
            'projects' => Project::query()
                ->ordered()
                ->get(['id', 'title', 'description', 'stack', 'url', 'sort_order', 'is_published']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Projects/Form', [
            'project' => null,
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Project::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Proyecto creado correctamente.')]);

        return to_route('admin.projects.index');
    }

    public function edit(Project $project): Response
    {
        return Inertia::render('admin/Projects/Form', [
            'project' => $project->only([
                'id',
                'title',
                'description',
                'stack',
                'url',
                'sort_order',
                'is_published',
            ]),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Proyecto actualizado correctamente.')]);

        return to_route('admin.projects.index');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Proyecto eliminado correctamente.')]);

        return to_route('admin.projects.index');
    }
}
