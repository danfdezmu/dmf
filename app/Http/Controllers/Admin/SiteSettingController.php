<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    public function edit(): Response
    {
        $settings = SiteSetting::current();

        return Inertia::render('admin/SiteSettings', [
            'settings' => [
                'brand_label' => $settings->brand_label,
                'name' => $settings->name,
                'role' => $settings->role,
                'intro' => $settings->intro,
                'linkedin_url' => $settings->linkedin_url,
                'contact_email' => $settings->contact_email,
                'work_steps_text' => implode("\n", $settings->work_steps ?? []),
                'services' => $settings->services ?? [],
                'education' => $settings->education ?? [],
            ],
        ]);
    }

    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        $settings = SiteSetting::current();
        $settings->update($request->siteSettingAttributes());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Sitio actualizado correctamente.')]);

        return to_route('admin.site.edit');
    }
}
