<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceRequest extends FormRequest
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
            'company' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'description' => ['nullable', 'string', 'max:3000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['sometimes', 'boolean'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'refresh_preview' => ['sometimes', 'boolean'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'remove_logo' => $this->boolean('remove_logo'),
            'refresh_preview' => $this->boolean('refresh_preview'),
            'ended_at' => $this->input('ended_at') === '' ? null : $this->input('ended_at'),
            'logo_url' => $this->input('logo_url') === '' ? null : $this->input('logo_url'),
            'website_url' => $this->input('website_url') === '' ? null : $this->input('website_url'),
        ]);
    }
}
