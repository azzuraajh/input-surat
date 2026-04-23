<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicOriginTest extends TestCase
{
    public function test_root_redirect_uses_forwarded_public_host_with_https(): void
    {
        $response = $this->withHeaders([
            'X-Forwarded-Host' => 'input-surat.emdns.biz.id',
        ])->get('/');

        $response->assertRedirect('https://input-surat.emdns.biz.id/login');
    }

    public function test_login_page_assets_use_forwarded_public_host_with_https(): void
    {
        $response = $this->withHeaders([
            'X-Forwarded-Host' => 'input-surat.emdns.biz.id',
        ])->get('/login');

        $response->assertOk();
        $response->assertSee('https://input-surat.emdns.biz.id/build/assets/', false);
    }
}
