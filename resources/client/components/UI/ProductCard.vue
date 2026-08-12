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

const placeholderGradient = computed(() => {
    const gradients = [
        'linear-gradient(135deg, #7a1220, #6b4c82)',
        'linear-gradient(135deg, #4f0a12, #c9a15a)',
        'linear-gradient(135deg, #6b4c82, #7a1220)',
        'linear-gradient(135deg, #c9a15a, #4f0a12)',
    ];
    return gradients[props.product.id % gradients.length];
});
</script>

<template>
    <div class="product-card fade-in">
        <Link :href="`/catalog/${product.slug}`" class="product-card__image-wrap">
            <div class="product-card__image">
                <div
                    class="product-card__placeholder"
                    :style="{ background: placeholderGradient }"
                    :class="{ '--hidden': imgLoaded && !imgError }"
                >
                    <span>&#x1F490;</span>
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
            <Link :href="`/catalog/${product.slug}`" class="product-card__name">
                {{ product.name }}
            </Link>
            <div class="product-card__bottom">
                <span class="product-card__price">{{ Number(product.price).toLocaleString('ru-RU') }} &#8381;</span>

                <button
                    v-if="quantity === 0"
                    class="product-card__add-btn"
                    @click="addItem(product)"
                >
                    В корзину
                </button>
                <div v-else class="product-card__counter">
                    <button class="product-card__counter-btn" @click="decrementItem(product.id)">-</button>
                    <span class="product-card__counter-value">{{ quantity }}</span>
                    <button class="product-card__counter-btn" @click="addItem(product)">+</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
$parchment: #f4ead9;
$ink: #1c1114;
$wine: #7a1220;
$gold: #c9a15a;

.product-card {
    background: $parchment;
    border-radius: 14px;
    overflow: hidden;
    color: $ink;
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
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
        font-size: 52px;
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

    &__name {
        display: block;
        font-family: 'Cormorant Garamond', serif;
        font-size: 19px;
        font-weight: 700;
        color: $ink;
        margin-bottom: 8px;
        transition: color 0.3s ease;

        &:hover {
            color: $wine;
        }
    }

    &__bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 12px;
    }

    &__price {
        font-weight: 600;
        color: $wine;
    }

    &__add-btn {
        background: $wine;
        color: #fff;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;

        &:hover {
            background: lighten($wine, 8%);
        }
    }

    &__counter {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid $wine;
        border-radius: 20px;
        overflow: hidden;
    }

    &__counter-btn {
        width: 28px;
        height: 28px;
        font-size: 14px;
        font-weight: 600;
        color: $wine;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;

        &:hover {
            background: $wine;
            color: #fff;
        }
    }

    &__counter-value {
        width: 24px;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: $wine;
    }
}
</style>
