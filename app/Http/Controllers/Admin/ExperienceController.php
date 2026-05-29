<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExperienceRequest;
use App\Http\Requests\Admin\UpdateExperienceRequest;
use App\Models\Experience;
use App\Services\LinkPreviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ExperienceController extends Controller
{
    public function __construct(private LinkPreviewService $linkPreview) {}

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
        Experience::query()->create(
            $this->experienceAttributes($request, new Experience),
        );

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
        $experience->update($this->experienceAttributes($request, $experience));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experiencia actualizada correctamente.')]);

        return to_route('admin.experiences.index');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $this->deleteStoredLogo($experience);

        $experience->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Experiencia eliminada correctamente.')]);

        return to_route('admin.experiences.index');
    }

    /**
     * @return array<string, mixed>
     */
    private function experienceAttributes(StoreExperienceRequest $request, Experience $experience): array
    {
        $attributes = $request->safe()->except(['logo', 'remove_logo', 'refresh_preview']);

        if ($request->boolean('remove_logo')) {
            $this->deleteStoredLogo($experience);
            $attributes['logo_url'] = null;
        }

        if ($request->hasFile('logo')) {
            $this->deleteStoredLogo($experience);
            $attributes['logo_url'] = $this->storeLogo($request->file('logo'));
        }

        $attributes['link_preview'] = $this->resolveLinkPreview(
            $attributes['website_url'] ?? null,
            $experience,
            $request->boolean('refresh_preview'),
        );

        return $attributes;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resolveLinkPreview(?string $websiteUrl, Experience $experience, bool $forceRefresh): ?array
    {
        if (blank($websiteUrl)) {
            return null;
        }

        if (
            ! $forceRefresh
            && $websiteUrl === $experience->website_url
            && is_array($experience->link_preview)
        ) {
            return $experience->link_preview;
        }

        return $this->linkPreview->fetch($websiteUrl);
    }

    private function storeLogo(UploadedFile $logo): string
    {
        return $logo->store('experience-logos', 'public');
    }

    private function deleteStoredLogo(Experience $experience): void
    {
        if (blank($experience->logo_url)) {
            return;
        }

        if (
            ! str_starts_with($experience->logo_url, 'http://')
            && ! str_starts_with($experience->logo_url, 'https://')
            && ! str_starts_with($experience->logo_url, '/')
            && Storage::disk('public')->exists($experience->logo_url)
        ) {
            Storage::disk('public')->delete($experience->logo_url);
        }
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
            'logo' => $experience->logo,
            'logo_url' => $experience->logo_url,
            'website_url' => $experience->website_url,
            'link_preview' => $experience->link_preview,
            'company_initials' => $experience->company_initials,
        ];
    }
}
