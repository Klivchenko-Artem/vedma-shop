<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import ProductCard from '@/components/UI/ProductCard.vue';

const props = defineProps({
    products: Array,
    categories: Array,
    filters: Object,
});

const sortBy = ref(props.filters?.sort || 'default');
const minPrice = ref(props.filters?.min_price || 0);
const maxPrice = ref(props.filters?.max_price || 10000);

const filteredProducts = computed(() => {
    let result = [...props.products];

    if (minPrice.value > 0) {
        result = result.filter(p => Number(p.price) >= minPrice.value);
    }

    if (maxPrice.value < 10000) {
        result = result.filter(p => Number(p.price) <= maxPrice.value);
    }

    if (sortBy.value === 'price_asc') {
        result.sort((a, b) => Number(a.price) - Number(b.price));
    } else if (sortBy.value === 'price_desc') {
        result.sort((a, b) => Number(b.price) - Number(a.price));
    } else if (sortBy.value === 'alpha') {
        result.sort((a, b) => a.name.localeCompare(b.name));
    }

    return result;
});

function resetFilters() {
    sortBy.value = 'default';
    minPrice.value = 0;
    maxPrice.value = 10000;
}
</script>

<template>
    <Head>
        <title>Каталог букетов — купить цветы в Таганроге | Ведьмина метла</title>
        <meta name="description" content="Каталог букетов цветочного салона «Ведьмина метла» в Таганроге. Большой выбор свежих букетов с доставкой по городу." />
    </Head>
    <div class="catalog section">
        <div class="container">
            <div class="section-head">
                <h3 class="catalog__title">Каталог букетов</h3>
            </div>

            <!-- Фильтры -->
            <div class="catalog__toolbar">
                <select v-model="sortBy" class="catalog__select">
                    <option value="default" selected>Сортировка: по умолчанию</option>
                    <option value="price_desc">Цена: по убыванию</option>
                    <option value="price_asc">Цена: по возрастанию</option>
                    <option value="alpha">По алфавиту</option>
                </select>

                <span class="catalog__spacer"></span>

                <div class="catalog__price-group">
                    <span class="catalog__label">Цена от</span>
                    <input
                        type="number"
                        v-model.number="minPrice"
                        class="catalog__price-input"
                        placeholder="0"
                        min="0"
                    />
                    <span class="catalog__label">до</span>
                    <input
                        type="number"
                        v-model.number="maxPrice"
                        class="catalog__price-input"
                        placeholder="10000"
                        min="0"
                    />
                </div>

                <span class="catalog__reset" @click="resetFilters">Сбросить</span>
            </div>

            <p class="catalog__count">Найдено {{ filteredProducts.length }} товаров</p>

            <div class="catalog__grid" v-if="filteredProducts.length">
                <ProductCard
                    v-for="product in filteredProducts"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <div v-else class="catalog__empty">
                <p>Товары не найдены</p>
                <button class="btn btn--secondary" @click="resetFilters">Сбросить фильтры</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
$parchment: #f4ead9;
$ink: #1c1114;
$wine: #7a1220;
$gold: #c9a15a;

.catalog {
    background: $parchment;
    padding: 50px 40px;
    color: $ink;
    flex: 1;

    @media (max-width: 768px) {
        padding: 32px 16px;
    }

    &__title {
        font-size: clamp(28px, 4vw, 34px);
        color: $ink;
        text-align: center;
        margin-bottom: 24px;
    }

    &__toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        background: #fff;
        border: 1px solid #e4d6bd;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 10px;

        @media (max-width: 768px) {
            flex-direction: column;
            align-items: stretch;
        }
    }

    &__select {
        border: 1px solid #d8c7a8;
        border-radius: 8px;
        padding: 8px 10px;
        font-family: 'Work Sans', sans-serif;
        font-size: 13px;
        background: #fff;
        color: $ink;
    }

    &__spacer {
        flex: 1;
    }

    &__price-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    &__label {
        font-size: 13px;
        color: #6b5f56;
    }

    &__price-input {
        width: 100px;
        border: 1px solid #d8c7a8;
        border-radius: 8px;
        padding: 8px 10px;
        font-family: 'Work Sans', sans-serif;
        font-size: 13px;
    }

    &__reset {
        color: $wine;
        font-size: 13px;
        cursor: pointer;
        text-decoration: underline;
        transition: color 0.3s ease;

        &:hover {
            color: darken($wine, 10%);
        }
    }

    &__count {
        font-size: 13px;
        color: #6b5f56;
        margin: 14px 0;
    }

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;

        @media (max-width: 900px) {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }

    &__empty {
        text-align: center;
        padding: 60px 20px;
        color: #6b5f56;

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
    }
}
</style>
