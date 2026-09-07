<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    /** Карта сайта отдаётся и является XML. */
    public function test_sitemap_is_xml(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml');
    }

    /** Товары попадают в карту сайта — иначе поисковик их не найдёт. */
    public function test_sitemap_contains_products(): void
    {
        $product = Product::factory()->create();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee($product->slug, false);
    }
}
