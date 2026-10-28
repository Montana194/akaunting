<?php

namespace Tests\Feature\Web;

use Illuminate\Support\Facades\URL;
use Tests\Feature\FeatureTestCase;

class UiSmokeTest extends FeatureTestCase
{
    public function testMainScreensRenderWithPublicDocumentRootAssets(): void
    {
        $this->loginAs();

        $routes = [
            'accounts.index', 'transactions.index', 'transfers.index',
            'customers.index', 'vendors.index', 'items.index',
            'invoices.index', 'bills.index', 'reports.index',
            'settings.company.edit', 'settings.localisation.edit', 'settings.invoice.edit',
            'categories.index', 'currencies.index', 'taxes.index', 'wizard.edit',
        ];

        if (getenv('UI_AUDIT_DIR')) {
            config(['app.url' => 'http://127.0.0.1:8002']);
            URL::forceRootUrl('http://127.0.0.1:8002');
        }

        foreach ($routes as $route) {
            $response = $this->get(route($route))->assertOk();
            $this->assertDoesNotMatchRegularExpression(
                '~(?:src|href)=[\'"][^\'"]*/public/(?:css|js|img|vendor)/~',
                $response->getContent(),
                "Invalid public asset URL on $route"
            );

            if (getenv('UI_AUDIT_DIR') && in_array($route, ['settings.company.edit', 'invoices.index', 'wizard.edit'])) {
                $directory = getenv('UI_AUDIT_DIR');
                if (! is_dir($directory)) {
                    mkdir($directory, 0775, true);
                }
                file_put_contents($directory . '/' . $route . '.html', $response->getContent());
            }
        }
    }
}
