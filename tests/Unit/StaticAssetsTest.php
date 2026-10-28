<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class StaticAssetsTest extends TestCase
{
    public function testLayoutAssetsResolveInsideThePublicDocumentRoot(): void
    {
        $root = dirname(__DIR__, 2);
        $heads = glob($root . '/resources/views/components/layouts/*/head.blade.php');

        foreach ($heads as $head) {
            preg_match_all('/asset\([\'"]([^\'"]+)[\'"]/', file_get_contents($head), $matches);

            foreach ($matches[1] as $asset) {
                $asset = explode('?', $asset)[0];

                // Dynamic values and remote assets are validated when rendering pages.
                if (str_contains($asset, '://') || ! pathinfo($asset, PATHINFO_EXTENSION)) {
                    continue;
                }

                $this->assertFileExists($root . '/public/' . ltrim($asset, '/'), "$head references a missing asset: $asset");
            }
        }
    }

    public function testWebAppManifestIconsExist(): void
    {
        $public = dirname(__DIR__, 2) . '/public';
        $manifest = json_decode(file_get_contents($public . '/manifest.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists($public . '/' . $icon['src']);
        }

        $this->assertFileExists($public . '/serviceworker.js');
    }
}
