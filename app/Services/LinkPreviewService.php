<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LinkPreviewService
{
    /**
     * @return array{title: string, description: string|null, image: string|null, url: string, domain: string}|null
     */
    public function fetch(string $url): ?array
    {
        $url = $this->normalizeUrl($url);

        if ($url === null) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; PortfolioBot/1.0)',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);

            if (! $response->successful()) {
                return $this->fallbackPreview($url);
            }

            $html = $response->body();

            $title = $this->metaContent($html, 'og:title')
                ?? $this->metaContent($html, 'twitter:title')
                ?? $this->titleTag($html)
                ?? $this->domainFromUrl($url);

            $description = $this->metaContent($html, 'og:description')
                ?? $this->metaContent($html, 'twitter:description')
                ?? $this->metaContent($html, 'description');

            $image = $this->metaContent($html, 'og:image')
                ?? $this->metaContent($html, 'twitter:image');

            if ($image !== null && ! str_starts_with($image, 'http')) {
                $image = $this->resolveRelativeUrl($url, $image);
            }

            return [
                'title' => Str::limit(html_entity_decode($title, ENT_QUOTES | ENT_HTML5), 120, ''),
                'description' => $description !== null
                    ? Str::limit(html_entity_decode($description, ENT_QUOTES | ENT_HTML5), 200, '')
                    : null,
                'image' => $image,
                'url' => $url,
                'domain' => $this->domainFromUrl($url),
            ];
        } catch (\Throwable) {
            return $this->fallbackPreview($url);
        }
    }

    private function normalizeUrl(string $url): ?string
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            $url = 'https://'.$url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    private function metaContent(string $html, string $property): ?string
    {
        $patterns = [
            '/<meta[^>]+(?:property|name)=["\']'.preg_quote($property, '/').'["\'][^>]+content=["\']([^"\']+)["\']/i',
            '/<meta[^>]+content=["\']([^"\']+)["\'][^>]+(?:property|name)=["\']'.preg_quote($property, '/').'["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    private function titleTag(string $html): ?string
    {
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function resolveRelativeUrl(string $baseUrl, string $path): string
    {
        $parts = parse_url($baseUrl);

        if (str_starts_with($path, '//')) {
            return ($parts['scheme'] ?? 'https').':'.$path;
        }

        if (str_starts_with($path, '/')) {
            return ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '').$path;
        }

        $base = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '');

        return rtrim($base, '/').'/'.ltrim($path, '/');
    }

    /**
     * @return array{title: string, description: null, image: null, url: string, domain: string}
     */
    private function fallbackPreview(string $url): array
    {
        return [
            'title' => $this->domainFromUrl($url),
            'description' => null,
            'image' => null,
            'url' => $url,
            'domain' => $this->domainFromUrl($url),
        ];
    }

    private function domainFromUrl(string $url): string
    {
        return parse_url($url, PHP_URL_HOST) ?? $url;
    }
}
