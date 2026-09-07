<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** Собирает корректное тело заказа для одного товара. */
    private function orderPayload(Product $product, array $override = []): array
    {
        return array_merge([
            'customer_name' => 'Иван Иванов',
            'customer_phone' => '+79001234567',
            'address' => 'Таганрог, ул. Петровская, 1',
            'comment' => 'Позвонить за час',
            'items' => [[
                'id' => $product->id,
                'name' => $product->name,
                'price' => 1500,
                'quantity' => 2,
            ]],
            'total' => 3000,
        ], $override);
    }

    /** Заказ с корректными данными сохраняется в базу. */
    public function test_valid_order_is_stored(): void
    {
        $product = Product::factory()->create();

        $this->post('/order', $this->orderPayload($product))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Иван Иванов',
            'customer_phone' => '+79001234567',
        ]);
    }

    /** Без телефона заказ не принимается. */
    public function test_order_without_phone_is_rejected(): void
    {
        $product = Product::factory()->create();

        $this->post('/order', $this->orderPayload($product, ['customer_phone' => '']))
            ->assertSessionHasErrors('customer_phone');

        $this->assertDatabaseCount('orders', 0);
    }

    /** Без адреса заказ не принимается. */
    public function test_order_without_address_is_rejected(): void
    {
        $product = Product::factory()->create();

        $this->post('/order', $this->orderPayload($product, ['address' => '']))
            ->assertSessionHasErrors('address');

        $this->assertDatabaseCount('orders', 0);
    }

    /** Пустая корзина — не заказ. */
    public function test_order_with_empty_cart_is_rejected(): void
    {
        $product = Product::factory()->create();

        $this->post('/order', $this->orderPayload($product, ['items' => []]))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('orders', 0);
    }

    /** Товара с таким id нет в базе — заказ отклоняется. */
    public function test_order_with_unknown_product_is_rejected(): void
    {
        $product = Product::factory()->create();
        $payload = $this->orderPayload($product);
        $payload['items'][0]['id'] = 999999;

        $this->post('/order', $payload)->assertSessionHasErrors('items.0.id');

        $this->assertDatabaseCount('orders', 0);
    }

    /** HTML в комментарии вырезается — защита от вставки разметки. */
    public function test_html_is_stripped_from_comment(): void
    {
        $product = Product::factory()->create();

        $this->post('/order', $this->orderPayload($product, [
            'comment' => 'Позвонить <script>alert(1)</script>',
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', [
            'comment' => 'Позвонить alert(1)',
        ]);
    }
}
