<script setup>
import { ref, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/stores/cart';

const props = defineProps({
    product: Object,
});

const { addItem, decrementItem, getItemQuantity } = useCart();
const quantity = computed(() => getItemQuantity(props.product.id));

const currentImageIndex = ref(0);
const realImages = computed(() => (props.product.images || []).filter(img => img && !img.startsWith('/')));
const images = realImages;

const mainLoaded = ref(false);
const mainError = ref(false);

watch(currentImageIndex, () => {
    mainLoaded.value = false;
    mainError.value = false;
});

function nextImage() {
    if (images.value.length === 0) return;
    currentImageIndex.value = (currentImageIndex.value + 1) % images.value.length;
}

function prevImage() {
    if (images.value.length === 0) return;
    currentImageIndex.value = (currentImageIndex.value - 1 + images.value.length) % images.value.length;
}

const placeholderColors = ['#e8ddd5', '#d5dde8', '#dde8d5', '#e8d5dd', '#d5e8dd', '#e0d8e8'];
function getColor(i) {
    return placeholderColors[(props.product.id + i) % placeholderColors.length];
}
</script>

<template>
    <div class="product-detail section">
        <div class="container">
            <div class="product-detail__breadcrumbs">
                <Link href="/">Главная</Link>
                <span>/</span>
                <Link href="/catalog">Каталог</Link>
                <span>/</span>
                <span class="--current">{{ product.name }}</span>
            </div>

            <div class="product-detail__content">
                <!-- Галерея -->
                <div class="product-detail__gallery">
                    <div class="product-detail__main-image">
                        <!-- Заглушка — всегда под картинкой, видна пока грузится или при ошибке -->
                        <div
                            class="product-detail__image-placeholder"
                            :style="{ backgroundColor: getColor(currentImageIndex) }"
                            :class="{ '--hidden': mainLoaded && !mainError }"
                        >
                            <span>🌸</span>
                        </div>

                        <!-- Реальное фото -->
                        <img
                            v-if="images.length && !mainError"
                            :key="currentImageIndex"
                            :src="`/storage/${images[currentImageIndex]}`"
                            :alt="product.name"
                            class="product-detail__img"
                            :class="{ '--visible': mainLoaded }"
                            @load="mainLoaded = true"
                            @error="mainError = true"
                        />

                        <!-- Стрелки -->
                        <button
                            v-if="images.length > 1"
                            class="product-detail__arrow --prev"
                            @click="prevImage"
                        >
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"/>
                            </svg>
                        </button>
                        <button
                            v-if="images.length > 1"
                            class="product-detail__arrow --next"
                            @click="nextImage"
                        >
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Превью-миниатюры -->
                    <div class="product-detail__thumbs" v-if="images.length > 1">
                        <button
                            v-for="(img, i) in images"
                            :key="i"
                            class="product-detail__thumb"
                            :class="{ '--active': i === currentImageIndex }"
                            @click="currentImageIndex = i"
                        >
                            <div class="product-detail__thumb-placeholder" :style="{ backgroundColor: getColor(i) }">
                                <span>🌸</span>
                            </div>
                            <img
                                :src="`/storage/${img}`"
                                :alt="`Фото ${i + 1}`"
                                @load="$event.target.classList.add('--loaded')"
                                @error="$event.target.style.display = 'none'"
                            />
                        </button>
                    </div>
                </div>

                <!-- Информация -->
                <div class="product-detail__info">
                    <span class="product-detail__category">{{ product.category?.name }}</span>
                    <h1 class="product-detail__name">{{ product.name }}</h1>
                    <p class="product-detail__price">{{ Number(product.price).toLocaleString('ru-RU') }} ₽</p>

                    <div class="product-detail__block" v-if="product.composition">
                        <h3 class="product-detail__block-title">Состав</h3>
                        <p class="product-detail__composition">{{ product.composition }}</p>
                    </div>

                    <div class="product-detail__block" v-if="product.description">
                        <h3 class="product-detail__block-title">Описание</h3>
                        <p class="product-detail__description">{{ product.description }}</p>
                    </div>

                    <div class="product-detail__actions">
                        <button
                            v-if="quantity === 0"
                            class="btn btn--primary"
                            @click="addItem(product)"
                        >
                            Добавить в корзину
                        </button>
                        <div v-else class="product-detail__cart-control">
                            <div class="product-detail__counter">
                                <button class="product-detail__counter-btn" @click="decrementItem(product.id)">−</button>
                                <span class="product-detail__counter-value">{{ quantity }}</span>
                                <button class="product-detail__counter-btn" @click="addItem(product)">+</button>
                            </div>
                            <span class="product-detail__in-cart">В корзине</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-white: #ffffff;

.product-detail {
    &__breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 32px;
        font-size: 13px;
        color: #999;

        a {
            transition: color 0.3s ease;
            &:hover { color: $color-primary; }
        }

        .--current {
            color: #333;
        }
    }

    &__content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: clamp(24px, 4vw, 60px);

        @media (max-width: 768px) {
            grid-template-columns: 1fr;
        }
    }

    &__gallery {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    &__main-image {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }

    &__image-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
        opacity: 0.6;
        transition: opacity 0.4s ease;
        z-index: 1;

        &.--hidden {
            opacity: 0;
            pointer-events: none;
        }
    }

    &__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 2;

        &.--visible {
            opacity: 1;
        }
    }

    &__arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba($color-white, 0.85);
        border-radius: 50%;
        color: #333;
        transition: all 0.3s ease;
        z-index: 3;

        &:hover {
            background: $color-white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        &.--prev { left: 12px; }
        &.--next { right: 12px; }
    }

    &__thumbs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    &__thumb {
        width: 64px;
        height: 64px;
        border-radius: 6px;
        overflow: hidden;
        position: relative;
        opacity: 0.5;
        border: 2px solid transparent;
        transition: all 0.3s ease;

        img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;

            &.--loaded {
                opacity: 1;
            }
        }

        &.--active {
            opacity: 1;
            border-color: $color-primary;
        }

        &:hover {
            opacity: 0.8;
        }
    }

    &__thumb-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        z-index: 1;
    }

    &__info {
        padding-top: 8px;
    }

    &__category {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        margin-bottom: 8px;
    }

    &__name {
        font-size: clamp(24px, 3vw, 36px);
        font-weight: 700;
        color: #2c2c2c;
        margin-bottom: 12px;
    }

    &__price {
        font-size: 28px;
        font-weight: 700;
        color: $color-primary;
        margin-bottom: 28px;
    }

    &__block {
        margin-bottom: 20px;
    }

    &__block-title {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
        margin-bottom: 8px;
    }

    &__composition, &__description {
        font-size: 15px;
        line-height: 1.7;
        color: #555;
    }

    &__actions {
        margin-top: 32px;
    }

    &__cart-control {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    &__counter {
        display: flex;
        align-items: center;
        border: 2px solid $color-primary;
        border-radius: 4px;
        overflow: hidden;
    }

    &__counter-btn {
        width: 44px;
        height: 44px;
        font-size: 20px;
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
        width: 44px;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        color: $color-primary;
    }

    &__in-cart {
        font-size: 14px;
        font-weight: 500;
        color: $color-accent;
    }
}
</style>
