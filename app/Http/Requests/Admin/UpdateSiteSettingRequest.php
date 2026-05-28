<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand_label' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'intro' => ['required', 'string', 'max:2000'],
            'linkedin_url' => ['required', 'url', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
            'work_steps_text' => ['required', 'string', 'max:5000'],
            'services' => ['required', 'array', 'min:1', 'max:6'],
            'services.*.title' => ['required', 'string', 'max:255'],
            'services.*.description' => ['required', 'string', 'max:1000'],
            'education' => ['required', 'array', 'min:1', 'max:5'],
            'education.*.institution' => ['required', 'string', 'max:255'],
            'education.*.degree' => ['required', 'string', 'max:255'],
            'education.*.field' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function siteSettingAttributes(): array
    {
        $validated = $this->validated();

        $validated['work_steps'] = collect(preg_split('/\r\n|\r|\n/', $validated['work_steps_text']))
            ->map(fn (string $step) => trim($step))
            ->filter()
            ->values()
            ->all();

        unset($validated['work_steps_text']);

        return $validated;
    }
}
