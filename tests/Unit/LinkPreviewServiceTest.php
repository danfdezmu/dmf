<?php

use App\Services\LinkPreviewService;
use Illuminate\Support\Facades\Http;

test('it extracts open graph metadata from a page', function () {
    Http::fake([
        'https://example.com' => Http::response(<<<'HTML'
            <html>
            <head>
                <meta property="og:title" content="Example Corp" />
                <meta property="og:description" content="Empresa de ejemplo." />
                <meta property="og:image" content="https://example.com/cover.jpg" />
            </head>
            </html>
        HTML),
    ]);

    $preview = app(LinkPreviewService::class)->fetch('https://example.com');

    expect($preview)->not->toBeNull()
        ->and($preview['title'])->toBe('Example Corp')
        ->and($preview['description'])->toBe('Empresa de ejemplo.')
        ->and($preview['image'])->toBe('https://example.com/cover.jpg')
        ->and($preview['domain'])->toBe('example.com');
});

test('it returns fallback preview when request fails', function () {
    Http::fake([
        'https://fallo.com' => Http::response('', 500),
    ]);

    $preview = app(LinkPreviewService::class)->fetch('fallo.com');

    expect($preview)->not->toBeNull()
        ->and($preview['title'])->toBe('fallo.com')
        ->and($preview['image'])->toBeNull();
});
