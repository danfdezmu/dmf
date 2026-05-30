<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'brand_label',
        'name',
        'role',
        'intro',
        'linkedin_url',
        'contact_email',
        'work_steps',
        'services',
        'education',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'work_steps' => 'array',
            'services' => 'array',
            'education' => 'array',
        ];
    }

    public static function current(): self
    {
        return self::query()->first() ?? self::query()->create(self::defaults());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'brand_label' => 'DMF',
            'name' => 'Dan Haziel Munoz Fernandez',
            'role' => 'Desarrollador de tecnologia',
            'intro' => 'Desarrollo soluciones web de punta a punta (FrontEnd y BackEnd) con React, TypeScript, Laravel y PHP. Experiencia en sector corporativo, educacion superior y practicas en industria automotriz.',
            'linkedin_url' => 'https://www.linkedin.com/in/dan-haziel-mu%C3%B1oz-fernandez-5b9073251/',
            'contact_email' => 'danhaziel.dev@gmail.com',
            'work_steps' => [
                'Entiendo tus objetivos de negocio y tiempos.',
                'Propongo una solucion clara y escalable.',
                'Entrego avances funcionales desde etapas tempranas.',
                'Ajustamos juntos hasta publicar.',
            ],
            'services' => [
                [
                    'title' => 'Desarrollo Web a Medida',
                    'description' => 'Construccion de aplicaciones y sistemas web enfocados en tus procesos de negocio.',
                ],
                [
                    'title' => 'Demos y MVP',
                    'description' => 'Versiones rapidas para presentar tu idea, validar mercado y buscar inversion.',
                ],
                [
                    'title' => 'Automatizacion de Procesos',
                    'description' => 'Soluciones para reducir tareas repetitivas y mejorar la productividad del equipo.',
                ],
            ],
            'education' => [
                [
                    'institution' => 'Universidad Autonoma del Estado de Morelos',
                    'degree' => 'Licenciatura',
                    'field' => 'Informatica',
                ],
            ],
        ];
    }
}
