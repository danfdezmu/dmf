<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = SiteSetting::query()->firstOrCreate([], SiteSetting::defaults());

        if (empty($settings->education)) {
            $settings->update(['education' => SiteSetting::defaults()['education']]);
        }

        if (Project::query()->doesntExist()) {
            Project::query()->insert([
                [
                    'title' => 'Portal Administrativo',
                    'description' => 'Panel para control de usuarios, reportes y operaciones diarias.',
                    'stack' => 'Laravel, Vue, MySQL',
                    'url' => null,
                    'sort_order' => 1,
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Sistema de Citas',
                    'description' => 'Agenda online con gestion de clientes y recordatorios.',
                    'stack' => 'Laravel, Inertia, Tailwind',
                    'url' => null,
                    'sort_order' => 2,
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Landing de Producto',
                    'description' => 'Sitio enfocado en conversion para captar clientes y solicitudes.',
                    'stack' => 'Laravel, Vue, SEO Basico',
                    'url' => null,
                    'sort_order' => 3,
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (Experience::query()->doesntExist()) {
            $now = now();

            Experience::query()->insert([
                [
                    'company' => 'Grupo Salinas',
                    'role' => 'Desarrollador de tecnologia',
                    'location' => 'Ciudad de Mexico, Mexico',
                    'started_at' => '2024-05-01',
                    'ended_at' => null,
                    'description' => 'Desarrollo de tecnologias FrontEnd y BackEnd con React.js, TypeScript y stack web moderno.',
                    'sort_order' => 1,
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'company' => 'Universidad Autonoma del Estado de Morelos',
                    'role' => 'Catedratico universitario',
                    'location' => 'Cuernavaca, Morelos, Mexico',
                    'started_at' => '2024-01-01',
                    'ended_at' => '2024-08-31',
                    'description' => 'Imparticion de la materia Estructura de Datos.',
                    'sort_order' => 2,
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'company' => 'Universidad Autonoma del Estado de Morelos',
                    'role' => 'Jefe de departamento',
                    'location' => 'Cuernavaca, Morelos, Mexico',
                    'started_at' => '2023-01-01',
                    'ended_at' => '2024-05-31',
                    'description' => 'Jefe de departamento de sistemas de control de informacion documentada.',
                    'sort_order' => 3,
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'company' => 'Universidad Autonoma del Estado de Morelos',
                    'role' => 'Asistente tecnico',
                    'location' => 'Cuernavaca, Morelos, Mexico',
                    'started_at' => '2019-09-01',
                    'ended_at' => '2023-01-31',
                    'description' => 'Desarrollo de sistemas para control de seguimiento en normas ISO.',
                    'sort_order' => 4,
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'company' => 'Nissan Mexicana',
                    'role' => 'Practicante universitario',
                    'location' => 'Jiutepec, Morelos, Mexico',
                    'started_at' => '2018-02-01',
                    'ended_at' => '2018-08-31',
                    'description' => 'Desarrollo de sistema de seguimiento interno de vehiculos de carga para control de metricas de tiempos dentro de la planta.',
                    'sort_order' => 5,
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        User::query()->firstOrCreate(
            ['email' => config('portfolio.admin_email')],
            [
                'name' => config('portfolio.admin_name'),
                'password' => config('portfolio.admin_password'),
                'email_verified_at' => now(),
            ],
        );
    }
}
