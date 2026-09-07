<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    /** Каталог открывается и отдаёт все товары. */
    public function test_catalog_lists_all_products(): void
    {
        Product::factory()->count(3)->create();

        $this->get('/catalog')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalog')
                ->has('products', 3)
            );
    }

    /** Фильтр по минимальной цене отсекает то, что дешевле. */
    public function test_catalog_filters_by_min_price(): void
    {
        Product::factory()->price(500)->create();
        Product::factory()->price(5000)->create();

        $this->get('/catalog?min_price=1000')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.price', '5000.00')
            );
    }

    /** Фильтр по максимальной цене отсекает то, что дороже. */
    public function test_catalog_filters_by_max_price(): void
    {
        Product::factory()->price(500)->create();
        Product::factory()->price(5000)->create();

        $this->get('/catalog?max_price=1000')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.price', '500.00')
            );
    }

    /** Сортировка по возрастанию цены. */
    public function test_catalog_sorts_by_price_ascending(): void
    {
        Product::factory()->price(3000)->create();
        Product::factory()->price(1000)->create();
        Product::factory()->price(2000)->create();

        $this->get('/catalog?sort=price_asc')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('products.0.price', '1000.00')
                ->where('products.2.price', '3000.00')
            );
    }

    /** Сортировка по убыванию цены. */
    public function test_catalog_sorts_by_price_descending(): void
    {
        Product::factory()->price(1000)->create();
        Product::factory()->price(3000)->create();

        $this->get('/catalog?sort=price_desc')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('products.0.price', '3000.00')
            );
    }

    /** Выбранные фильтры возвращаются на фронт, чтобы форма не сбрасывалась. */
    public function test_catalog_returns_applied_filters(): void
    {
        $this->get('/catalog?min_price=100&sort=price_asc')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('filters.min_price', '100')
                ->where('filters.sort', 'price_asc')
            );
    }
}
