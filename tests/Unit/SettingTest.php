<?php

namespace Tests\Unit;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /** Нет такой настройки — отдаём значение по умолчанию, а не падаем. */
    public function test_missing_key_returns_default(): void
    {
        $this->assertNull(Setting::getValue('nesushchestvuyushchiy_klyuch'));
        $this->assertSame('запасной', Setting::getValue('nesushchestvuyushchiy_klyuch', 'запасной'));
    }

    /** Записали — прочитали. */
    public function test_value_is_saved_and_read_back(): void
    {
        Setting::setValue('home_title', 'Ведьмина метла');

        $this->assertSame('Ведьмина метла', Setting::getValue('home_title'));
    }

    /** Массивы сохраняются и достаются массивами — на этом держатся блоки главной. */
    public function test_array_value_survives_round_trip(): void
    {
        Setting::setValue('home_feature_descs', ['Свежие цветы', 'Доставка за час']);

        $this->assertSame(['Свежие цветы', 'Доставка за час'], Setting::getValue('home_feature_descs'));
    }

    /** Повторная запись обновляет значение, а не плодит строки. */
    public function test_repeated_write_updates_same_row(): void
    {
        Setting::setValue('home_title', 'Первое');
        Setting::setValue('home_title', 'Второе');

        $this->assertSame('Второе', Setting::getValue('home_title'));
        $this->assertDatabaseCount('settings', 1);
    }
}
