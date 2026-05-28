<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $settings = SiteSetting::current();

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
            'experiences' => Experience::query()
                ->published()
                ->ordered()
                ->get()
                ->map(fn (Experience $experience) => [
                    'id' => $experience->id,
                    'company' => $experience->company,
                    'role' => $experience->role,
                    'location' => $experience->location,
                    'period' => $experience->period_label,
                    'description' => $experience->description,
                ]),
        ]);
    }
}
