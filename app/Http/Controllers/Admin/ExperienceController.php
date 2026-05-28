<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExperienceRequest;
use App\Http\Requests\Admin\UpdateExperienceRequest;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ExperienceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Experiences/Index', [
            'experiences' => Experience::query()
                ->ordered()
                ->get()
                ->map(fn (Experience $experience) => $this->experiencePayload($experience)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Experiences/Form', [
            'experience' => null,
        ]);
    }

    public function store(StoreExperienceRequest $request): RedirectResponse
    {
        Experience::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experiencia creada correctamente.')]);

        return to_route('admin.experiences.index');
    }

    public function edit(Experience $experience): Response
    {
        return Inertia::render('admin/Experiences/Form', [
            'experience' => $this->experiencePayload($experience),
        ]);
    }

    public function update(UpdateExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $experience->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experiencia actualizada correctamente.')]);

        return to_route('admin.experiences.index');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experiencia eliminada correctamente.')]);

        return to_route('admin.experiences.index');
    }

    /**
     * @return array<string, mixed>
     */
    private function experiencePayload(Experience $experience): array
    {
        return [
            'id' => $experience->id,
            'company' => $experience->company,
            'role' => $experience->role,
            'location' => $experience->location,
            'started_at' => $experience->started_at?->format('Y-m-d'),
            'ended_at' => $experience->ended_at?->format('Y-m-d'),
            'description' => $experience->description,
            'sort_order' => $experience->sort_order,
            'is_published' => $experience->is_published,
            'period' => $experience->period_label,
        ];
    }
}
