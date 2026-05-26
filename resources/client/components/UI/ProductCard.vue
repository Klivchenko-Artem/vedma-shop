<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/stores/cart';

const props = defineProps({
    product: Object,
});

const { addItem, decrementItem, getItemQuantity } = useCart();

const quantity = computed(() => getItemQuantity(props.product.id));

const imgLoaded = ref(false);
const imgError = ref(false);
function isRealImage(path) {
    return path && !path.startsWith('/');
}

const firstImage = computed(() => {
    const imgs = props.product.images;
    if (!imgs || !imgs.length) return null;
    const img = imgs.find(isRealImage);
    return img ? `/storage/${img}` : null;
});

const placeholderColor = computed(() => {
    const colors = ['#e8ddd5', '#d5dde8', '#dde8d5', '#e8d5dd', '#d5e8dd', '#e0d8e8'];
    return colors[props.product.id % colors.length];
});
</script>

<template>
    <div class="product-card fade-in">
        <Link :href="`/catalog/${product.slug}`" class="product-card__image-wrap">
            <div class="product-card__image">
                <div
                    class="product-card__placeholder"
                    :style="{ backgroundColor: placeholderColor }"
                    :class="{ '--hidden': imgLoaded && !imgError }"
                >
                    <span>🌸</span>
                </div>
                <img
                    v-if="firstImage && !imgError"
                    :src="firstImage"
                    :alt="product.name"
                    class="product-card__img"
                    :class="{ '--visible': imgLoaded }"
                    @load="imgLoaded = true"
                    @error="imgError = true"
                />
            </div>
        </Link>

        <div class="product-card__info">
            <span class="product-card__category">{{ product.category?.name }}</span>
            <Link :href="`/catalog/${product.slug}`" class="product-card__name">
                {{ product.name }}
            </Link>
            <div class="product-card__bottom">
                <span class="product-card__price">{{ Number(product.price).toLocaleString('ru-RU') }} ₽</span>

                <button
                    v-if="quantity === 0"
                    class="product-card__add-btn"
                    @click="addItem(product)"
                >
                    В корзину
                </button>
                <div v-else class="product-card__counter">
                    <button class="product-card__counter-btn" @click="decrementItem(product.id)">−</button>
                    <span class="product-card__counter-value">{{ quantity }}</span>
                    <button class="product-card__counter-btn" @click="addItem(product)">+</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-glow: rgba(45, 74, 45, 0.3);
$color-white: #ffffff;

.product-card {
    background: $color-white;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    &__image-wrap {
        display: block;
        overflow: hidden;
    }

    &__image {
        aspect-ratio: 3 / 4;
        overflow: hidden;
        position: relative;
    }

    &__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.4s ease, transform 0.5s ease;

        &.--visible {
            opacity: 1;
        }

        .product-card:hover & {
            transform: scale(1.05);
        }
    }

    &__placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        opacity: 0.5;
        transition: opacity 0.4s ease, transform 0.5s ease;

        &.--hidden {
            opacity: 0;
        }

        .product-card:hover & {
            transform: scale(1.05);
        }
    }

    &__info {
        padding: 16px;
    }

    &__category {
        display: block;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 4px;
    }

    &__name {
        display: block;
        font-size: 16px;
        font-weight: 600;
        color: #2c2c2c;
        margin-bottom: 12px;
        transition: color 0.3s ease;

        &:hover {
            color: $color-primary;
        }
    }

    &__bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    &__price {
        font-size: 18px;
        font-weight: 700;
        color: $color-primary;
    }

    &__add-btn {
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: $color-white;
        background: $color-primary;
        border-radius: 4px;
        transition: all 0.3s ease;

        &:hover {
            background: adjust-color($color-primary, $lightness: 8%);
            box-shadow: 0 4px 16px $color-glow;
        }
    }

    &__counter {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid $color-primary;
        border-radius: 4px;
        overflow: hidden;
    }

    &__counter-btn {
        width: 32px;
        height: 32px;
        font-size: 16px;
        font-weight: 600;
        color: $color-primary;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;

        &:hover {
            background: $color-primary;
            color: $color-white;
        }
    }

    &__counter-value {
        width: 28px;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        color: $color-primary;
    }
}
</style>
