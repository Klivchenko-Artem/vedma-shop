<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    /** Карточка товара открывается по своему slug. */
    public function test_product_page_opens_by_slug(): void
    {
        $product = Product::factory()->create(['name' => 'Букет из пионов']);

        $this->get('/catalog/' . $product->slug)
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('ProductDetail')
                ->where('product.name', 'Букет из пионов')
            );
    }

    /** Несуществующий товар отдаёт 404, а не пустую страницу. */
    public function test_unknown_product_returns_404(): void
    {
        $this->get('/catalog/takogo-tovara-net')->assertNotFound();
    }
}
