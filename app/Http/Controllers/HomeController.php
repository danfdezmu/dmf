<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $settings = SiteSetting::current();

        $experiences = Experience::query()
            ->published()
            ->ordered()
            ->get();

        return Inertia::render('Welcome', [
            'profile' => [
                'name' => $settings->name,
                'role' => $settings->role,
                'intro' => $settings->intro,
                'linkedin' => $settings->linkedin_url,
                'email' => $settings->contact_email,
                'brand' => $settings->brand_label,
            ],
            'workSteps' => $settings->work_steps,
            'services' => $settings->services,
            'education' => $settings->education ?? [],
            'projects' => Project::query()
                ->published()
                ->ordered()
                ->get(['id', 'title', 'description', 'stack', 'url']),
            'experiences' => $experiences->map(fn (Experience $experience) => [
                'id' => $experience->id,
                'company' => $experience->company,
                'role' => $experience->role,
                'location' => $experience->location,
                'period' => $experience->period_label,
                'description' => $experience->description,
                'logo' => $experience->logo,
                'initials' => $experience->company_initials,
                'website_url' => $experience->website_url,
                'link_preview' => $experience->link_preview,
            ]),
            'companyWebsites' => $this->uniqueCompanyWebsites($experiences),
        ]);
    }

    /**
     * @param  Collection<int, Experience>  $experiences
     * @return list<array{company: string, website_url: string, link_preview: array<string, mixed>|null, logo: string|null, initials: string}>
     */
    private function uniqueCompanyWebsites($experiences): array
    {
        return $experiences
            ->filter(fn (Experience $experience) => filled($experience->website_url))
            ->unique('website_url')
            ->values()
            ->map(fn (Experience $experience) => [
                'company' => $experience->company,
                'website_url' => $experience->website_url,
                'link_preview' => $experience->link_preview,
                'logo' => $experience->logo,
                'initials' => $experience->company_initials,
            ])
            ->all();
    }
}
