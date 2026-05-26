<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class VedmaSeeder extends Seeder
{
    public function run(): void
    {
        // Категории
        $categories = [
            ['name' => 'Букеты', 'slug' => 'bukety', 'sort_order' => 1],
            ['name' => 'Одиночные цветы', 'slug' => 'odinochnye-tsvety', 'sort_order' => 2],
            ['name' => 'Мягкие игрушки', 'slug' => 'myagkie-igrushki', 'sort_order' => 3],
            ['name' => 'Живые цветы', 'slug' => 'zhivye-tsvety', 'sort_order' => 4],
            ['name' => 'Цветы в горшках', 'slug' => 'tsvety-v-gorshkah', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Товары
        $products = [
            // Букеты
            [
                'category_id' => 1, 'name' => 'Ночная орхидея', 'slug' => 'nochnaya-orhideya',
                'description' => 'Элегантный букет из тёмных орхидей с зеленью эвкалипта. Идеален для вечерних мероприятий и создания загадочной атмосферы.',
                'composition' => 'Орхидея тёмная — 5 шт, Эвкалипт — 3 ветки, Рускус — 4 шт',
                'price' => 1200, 'is_popular' => true, 'sort_order' => 1,
                'images' => ['/images/products/placeholder-1.jpg', '/images/products/placeholder-2.jpg', '/images/products/placeholder-3.jpg'],
            ],
            [
                'category_id' => 1, 'name' => 'Ведьмина метла', 'slug' => 'vedmina-metla',
                'description' => 'Авторский букет с сухоцветами, лавандой и ветками. Магический стиль — для тех, кто ценит необычное.',
                'composition' => 'Лаванда сухая — 7 шт, Пампасная трава — 3 шт, Ветки декоративные — 5 шт, Розы кустовые — 4 шт',
                'price' => 1500, 'is_popular' => true, 'sort_order' => 2,
                'images' => ['/images/products/placeholder-4.jpg', '/images/products/placeholder-5.jpg', '/images/products/placeholder-6.jpg'],
            ],
            [
                'category_id' => 1, 'name' => 'Лунный свет', 'slug' => 'lunnyj-svet',
                'description' => 'Нежный букет из белых роз и гипсофилы. Чистота и невинность в каждом лепестке.',
                'composition' => 'Розы белые — 11 шт, Гипсофила — 5 шт, Зелень — 3 ветки',
                'price' => 2200, 'is_popular' => true, 'sort_order' => 3,
                'images' => ['/images/products/placeholder-7.jpg', '/images/products/placeholder-8.jpg', '/images/products/placeholder-9.jpg'],
            ],
            [
                'category_id' => 1, 'name' => 'Пепел и роза', 'slug' => 'pepel-i-roza',
                'description' => 'Контрастный букет из пепельно-розовых роз с серебристой зеленью.',
                'composition' => 'Розы пепельно-розовые — 9 шт, Эвкалипт серебристый — 4 ветки, Сенецио — 3 шт',
                'price' => 1800, 'is_popular' => true, 'sort_order' => 4,
                'images' => ['/images/products/placeholder-10.jpg', '/images/products/placeholder-11.jpg', '/images/products/placeholder-12.jpg'],
            ],
            [
                'category_id' => 1, 'name' => 'Тёмный бархат', 'slug' => 'tyomnyj-barhat',
                'description' => 'Роскошный букет из бордовых роз и чёрных калл.',
                'composition' => 'Розы бордовые — 7 шт, Каллы тёмные — 3 шт, Питтоспорум — 5 веток',
                'price' => 2500, 'is_popular' => false, 'sort_order' => 5,
                'images' => ['/images/products/placeholder-1.jpg', '/images/products/placeholder-4.jpg', '/images/products/placeholder-7.jpg'],
            ],

            // Одиночные цветы
            [
                'category_id' => 2, 'name' => 'Роза красная', 'slug' => 'roza-krasnaya',
                'description' => 'Классическая красная роза премиум-класса, длина стебля 70 см.',
                'composition' => 'Роза красная — 1 шт',
                'price' => 300, 'is_popular' => false, 'sort_order' => 1,
                'images' => ['/images/products/placeholder-2.jpg', '/images/products/placeholder-5.jpg'],
            ],
            [
                'category_id' => 2, 'name' => 'Пион розовый', 'slug' => 'pion-rozovyj',
                'description' => 'Пышный розовый пион, символ благополучия и романтики.',
                'composition' => 'Пион розовый — 1 шт',
                'price' => 450, 'is_popular' => false, 'sort_order' => 2,
                'images' => ['/images/products/placeholder-3.jpg', '/images/products/placeholder-6.jpg'],
            ],
            [
                'category_id' => 2, 'name' => 'Хризантема белая', 'slug' => 'hrizantema-belaya',
                'description' => 'Элегантная белая хризантема с пышными лепестками.',
                'composition' => 'Хризантема кустовая — 1 шт',
                'price' => 250, 'is_popular' => false, 'sort_order' => 3,
                'images' => ['/images/products/placeholder-8.jpg', '/images/products/placeholder-11.jpg'],
            ],

            // Мягкие игрушки
            [
                'category_id' => 3, 'name' => 'Кот в горшке', 'slug' => 'kot-v-gorshke',
                'description' => 'Очаровательный плюшевый котик в цветочном горшке. Отличный подарок!',
                'composition' => 'Мягкая игрушка «Кот» — 1 шт, Декоративный горшок — 1 шт',
                'price' => 800, 'is_popular' => false, 'sort_order' => 1,
                'images' => ['/images/products/placeholder-9.jpg', '/images/products/placeholder-12.jpg'],
            ],
            [
                'category_id' => 3, 'name' => 'Мишка с букетом', 'slug' => 'mishka-s-buketom',
                'description' => 'Плюшевый медвежонок с мини-букетом из сухоцветов.',
                'composition' => 'Мягкая игрушка «Мишка» — 1 шт, Мини-букет — 1 шт',
                'price' => 1100, 'is_popular' => false, 'sort_order' => 2,
                'images' => ['/images/products/placeholder-1.jpg', '/images/products/placeholder-10.jpg'],
            ],

            // Живые цветы
            [
                'category_id' => 4, 'name' => 'Лаванда', 'slug' => 'lavanda',
                'description' => 'Ароматная лаванда в стильной упаковке. Создаёт атмосферу Прованса.',
                'composition' => 'Лаванда свежая — 7 стеблей',
                'price' => 600, 'is_popular' => false, 'sort_order' => 1,
                'images' => ['/images/products/placeholder-4.jpg', '/images/products/placeholder-7.jpg'],
            ],
            [
                'category_id' => 4, 'name' => 'Эустома', 'slug' => 'eustoma',
                'description' => 'Нежная эустома, похожая на маленькую розу. Долго стоит в вазе.',
                'composition' => 'Эустома — 5 стеблей',
                'price' => 550, 'is_popular' => false, 'sort_order' => 2,
                'images' => ['/images/products/placeholder-2.jpg', '/images/products/placeholder-8.jpg'],
            ],

            // Цветы в горшках
            [
                'category_id' => 5, 'name' => 'Замиокулькас', 'slug' => 'zamiokulkas',
                'description' => 'Неприхотливый замиокулькас в стильном кашпо. Идеальное растение для дома и офиса.',
                'composition' => 'Замиокулькас — 1 шт, Кашпо декоративное — 1 шт',
                'price' => 2000, 'is_popular' => false, 'sort_order' => 1,
                'images' => ['/images/products/placeholder-5.jpg', '/images/products/placeholder-9.jpg'],
            ],
            [
                'category_id' => 5, 'name' => 'Фикус Бенджамина', 'slug' => 'fikus-bendzhamin',
                'description' => 'Элегантный фикус в белом горшке. Очищает воздух и радует глаз.',
                'composition' => 'Фикус Бенджамина — 1 шт, Горшок белый — 1 шт',
                'price' => 1500, 'is_popular' => false, 'sort_order' => 2,
                'images' => ['/images/products/placeholder-6.jpg', '/images/products/placeholder-3.jpg'],
            ],
            [
                'category_id' => 5, 'name' => 'Суккулент микс', 'slug' => 'sukkulenty-miks',
                'description' => 'Композиция из 3 суккулентов в стеклянном флорариуме.',
                'composition' => 'Суккуленты разные — 3 шт, Флорариум стеклянный — 1 шт, Декоративный мох — 1 порция',
                'price' => 1200, 'is_popular' => false, 'sort_order' => 3,
                'images' => ['/images/products/placeholder-10.jpg', '/images/products/placeholder-11.jpg'],
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Отзывы
        $reviews = [
            ['author' => 'Анна М.', 'text' => 'Заказывала букет «Ночная орхидея» на день рождения подруги. Букет превзошёл все ожидания! Свежие цветы, красивая упаковка. Доставили точно в срок.', 'rating' => 5],
            ['author' => 'Дмитрий К.', 'text' => 'Третий раз заказываю здесь. Качество всегда на высоте, цены адекватные. Жена в восторге от «Ведьминой метлы» — очень необычный букет!', 'rating' => 5],
            ['author' => 'Екатерина В.', 'text' => 'Купила замиокулькас для офиса. Растение здоровое, горшок стильный. Коллеги спрашивают, где заказывала. Рекомендую!', 'rating' => 5],
            ['author' => 'Мария С.', 'text' => 'Очень приятный магазин! Курьер был вежливый, букет довезли в идеальном состоянии. Буду заказывать ещё!', 'rating' => 4],
            ['author' => 'Олег Р.', 'text' => 'Лаванда была свежайшая! Аромат наполнил весь дом. Отличный сервис, быстрая доставка.', 'rating' => 5],
            ['author' => 'Ирина Т.', 'text' => 'Замечательные букеты! «Пепел и роза» — просто шедевр. Нежные цвета, идеальная композиция. Спасибо!', 'rating' => 5],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

        // Галерея
        for ($i = 1; $i <= 12; $i++) {
            Gallery::create([
                'title' => "Букет №$i",
                'image' => "/images/gallery/placeholder-$i.jpg",
                'sort_order' => $i,
            ]);
        }
    }
}
