<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** Главная открывается даже на пустой базе. */
    public function test_home_page_opens(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Home'));
    }

    /** На главную попадают только популярные товары, максимум четыре. */
    public function test_home_shows_only_four_popular_products(): void
    {
        Product::factory()->count(6)->popular()->create();
        Product::factory()->count(3)->create();

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('popularProducts', 4)
            );
    }

    /** Отзывы уходят на главную в порядке от свежих к старым. */
    public function test_home_shows_reviews_newest_first(): void
    {
        $old = Review::factory()->create(['author' => 'Старый', 'created_at' => now()->subDay()]);
        $new = Review::factory()->create(['author' => 'Новый', 'created_at' => now()]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('reviews', 2)
                ->where('reviews.0.author', 'Новый')
                ->where('reviews.1.author', 'Старый')
            );
    }
}
