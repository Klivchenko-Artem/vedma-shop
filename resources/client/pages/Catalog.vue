<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import ProductCard from '@/components/UI/ProductCard.vue';

const props = defineProps({
    products: Array,
    categories: Array,
    filters: Object,
});

const selectedCategory = ref(props.filters?.category || '');
const sortBy = ref(props.filters?.sort || 'default');
const minPrice = ref(props.filters?.min_price || 0);
const maxPrice = ref(props.filters?.max_price || 10000);

// Фильтрация товаров на клиенте (быстрее, без перезагрузки)
const filteredProducts = computed(() => {
    let result = [...props.products];

    if (selectedCategory.value) {
        result = result.filter(p => p.category_id === Number(selectedCategory.value));
    }

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
    }

    return result;
});

function resetFilters() {
    selectedCategory.value = '';
    sortBy.value = 'default';
    minPrice.value = 0;
    maxPrice.value = 10000;
}
</script>

<template>
    <div class="catalog section">
        <div class="container">
            <h1 class="section-title">Каталог</h1>

            <!-- Фильтры -->
            <div class="catalog__filters">
                <div class="catalog__filter-group">
                    <label class="catalog__filter-label">Категория</label>
                    <select v-model="selectedCategory" class="catalog__select">
                        <option value="">Все</option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <div class="catalog__filter-group">
                    <label class="catalog__filter-label">Сортировка</label>
                    <select v-model="sortBy" class="catalog__select">
                        <option value="default">По умолчанию</option>
                        <option value="price_asc">Цена: по возрастанию</option>
                        <option value="price_desc">Цена: по убыванию</option>
                    </select>
                </div>

                <div class="catalog__filter-group catalog__filter-group--range">
                    <label class="catalog__filter-label">Цена</label>
                    <div class="catalog__range-inputs">
                        <input
                            type="number"
                            v-model.number="minPrice"
                            class="catalog__range-input"
                            placeholder="от"
                            min="0"
                        />
                        <span class="catalog__range-sep">—</span>
                        <input
                            type="number"
                            v-model.number="maxPrice"
                            class="catalog__range-input"
                            placeholder="до"
                            min="0"
                        />
                    </div>
                </div>

                <div class="catalog__filter-group">
                    <label class="catalog__filter-label">&nbsp;</label>
                    <button class="btn btn--secondary btn--small" @click="resetFilters">
                        Сбросить
                    </button>
                </div>
            </div>

            <!-- Количество -->
            <p class="catalog__count">
                Найдено: {{ filteredProducts.length }} товаров
            </p>

            <!-- Сетка товаров -->
            <div class="catalog__grid" v-if="filteredProducts.length">
                <ProductCard
                    v-for="product in filteredProducts"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <div v-else class="catalog__empty">
                <span>🔍</span>
                <p>Товары не найдены</p>
                <button class="btn btn--secondary" @click="resetFilters">Сбросить фильтры</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-white: #ffffff;

.catalog {
    &__filters {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px 24px;
        background: $color-white;
        border-radius: 8px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);

        @media (max-width: 768px) {
            flex-direction: column;
        }
    }

    &__filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 160px;

        &--range {
            min-width: 200px;
        }
    }

    &__filter-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
    }

    &__select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        background: $color-white;
        color: #333;
        transition: border-color 0.3s ease;

        &:focus {
            border-color: $color-primary;
        }
    }

    &__range-inputs {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    &__range-input {
        width: 80px;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;

        &:focus {
            border-color: $color-primary;
        }
    }

    &__range-sep {
        color: #ccc;
    }

    &__count {
        font-size: 13px;
        color: #999;
        margin-bottom: 20px;
    }

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;

        @media (max-width: 1024px) {
            grid-template-columns: repeat(3, 1fr);
        }

        @media (max-width: 768px) {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }

    &__empty {
        text-align: center;
        padding: 60px 20px;
        color: #999;

        span {
            font-size: 48px;
            display: block;
            margin-bottom: 16px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
    }
}
</style>
