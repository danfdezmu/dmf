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
            $table->string('website_url')->nullable()->after('logo_url');
            $table->json('link_preview')->nullable()->after('website_url');
        });

        $websitesByCompany = [
            'Grupo Salinas' => 'https://www.gruposalinas.com',
            'Universidad Autonoma del Estado de Morelos' => 'https://www.uaem.mx',
            'Nissan Mexicana' => 'https://www.nissan.com.mx',
        ];

        foreach ($websitesByCompany as $company => $websiteUrl) {
            DB::table('experiences')
                ->where('company', $company)
                ->whereNull('website_url')
                ->update(['website_url' => $websiteUrl]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['website_url', 'link_preview']);
        });
    }
};
