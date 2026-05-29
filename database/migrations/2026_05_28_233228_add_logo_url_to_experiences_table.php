<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->after('company');
        });

        $logosByCompany = [
            'Grupo Salinas' => '/images/logos/grupo-salinas.svg',
            'Universidad Autonoma del Estado de Morelos' => '/images/logos/uaem.svg',
            'Nissan Mexicana' => '/images/logos/nissan.svg',
        ];

        foreach ($logosByCompany as $company => $logoUrl) {
            DB::table('experiences')
                ->where('company', $company)
                ->whereNull('logo_url')
                ->update(['logo_url' => $logoUrl]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });
    }
};
