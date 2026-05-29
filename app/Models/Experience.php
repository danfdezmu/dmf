<?php

namespace App\Models;

use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    /** @use HasFactory<ExperienceFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'company',
        'logo_url',
        'website_url',
        'link_preview',
        'role',
        'location',
        'started_at',
        'ended_at',
        'description',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
            'is_published' => 'boolean',
            'link_preview' => 'array',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function periodLabel(): Attribute
    {
        return Attribute::get(function (): string {
            $start = $this->started_at?->locale('es')->translatedFormat('M Y') ?? '';
            $end = $this->ended_at?->locale('es')->translatedFormat('M Y') ?? 'Presente';

            return trim("{$start} – {$end}");
        });
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function logo(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (blank($this->logo_url)) {
                return null;
            }

            if (str_starts_with($this->logo_url, 'http://') || str_starts_with($this->logo_url, 'https://')) {
                return $this->logo_url;
            }

            if (str_starts_with($this->logo_url, '/')) {
                return $this->logo_url;
            }

            return '/storage/'.$this->logo_url;
        });
    }

    /**
     * @return Attribute<string, never>
     */
    protected function companyInitials(): Attribute
    {
        return Attribute::get(function (): string {
            $words = preg_split('/\s+/', trim($this->company)) ?: [];

            if (count($words) === 0) {
                return '?';
            }

            if (count($words) === 1) {
                return mb_strtoupper(mb_substr($words[0], 0, 2));
            }

            return mb_strtoupper(mb_substr($words[0], 0, 1).mb_substr($words[1], 0, 1));
        });
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderByDesc('started_at')->orderBy('sort_order')->orderBy('id');
    }
}
