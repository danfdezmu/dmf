<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    /** @use HasFactory<\Database\Factories\ExperienceFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'company',
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
