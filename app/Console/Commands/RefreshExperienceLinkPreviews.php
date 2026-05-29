<?php

namespace App\Console\Commands;

use App\Models\Experience;
use App\Services\LinkPreviewService;
use Illuminate\Console\Command;

class RefreshExperienceLinkPreviews extends Command
{
    protected $signature = 'experience:refresh-previews';

    protected $description = 'Actualiza las vistas previas de los sitios web de las experiencias laborales';

    public function handle(LinkPreviewService $linkPreview): int
    {
        $experiences = Experience::query()
            ->whereNotNull('website_url')
            ->get();

        if ($experiences->isEmpty()) {
            $this->warn('No hay experiencias con sitio web configurado.');

            return self::SUCCESS;
        }

        foreach ($experiences as $experience) {
            $preview = $linkPreview->fetch($experience->website_url);

            $experience->update(['link_preview' => $preview]);

            $this->line("✓ {$experience->company}");
        }

        $this->info('Vistas previas actualizadas.');

        return self::SUCCESS;
    }
}
