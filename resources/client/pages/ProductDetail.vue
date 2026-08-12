<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
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

const gradients = [
    'linear-gradient(135deg, #7a1220, #6b4c82)',
    'linear-gradient(135deg, #4f0a12, #c9a15a)',
    'linear-gradient(135deg, #6b4c82, #7a1220)',
    'linear-gradient(135deg, #c9a15a, #4f0a12)',
];

function getGradient(i) {
    return gradients[(props.product.id + i) % gradients.length];
}
</script>

<template>
    <Head>
        <title>{{ product.name }} — купить в Таганроге | Ведьмина метла</title>
        <meta name="description" :content="`${product.name} — ${product.description || 'букет из свежих цветов'}. Купить с доставкой по Таганрогу в салоне Ведьмина метла.`" />
    </Head>
    <div class="product-detail section">
        <div class="container">
            <div class="product-detail__content">
                <!-- Галерея -->
                <div class="product-detail__gallery">
                    <div class="product-detail__main-image">
                        <div
                            class="product-detail__image-placeholder"
                            :style="{ background: getGradient(currentImageIndex) }"
                            :class="{ '--hidden': mainLoaded && !mainError }"
                        ></div>

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

                        <button
                            v-if="images.length > 1"
                            class="product-detail__arrow --prev"
                            @click="prevImage"
                        >&lsaquo;</button>
                        <button
                            v-if="images.length > 1"
                            class="product-detail__arrow --next"
                            @click="nextImage"
                        >&rsaquo;</button>
                    </div>

                    <div class="product-detail__thumbs" v-if="images.length > 1">
                        <div
                            v-for="(img, i) in images"
                            :key="i"
                            class="product-detail__thumb"
                            :class="{ '--active': i === currentImageIndex }"
                            @click="currentImageIndex = i"
                        >
                            <div class="product-detail__thumb-bg" :style="{ background: getGradient(i) }"></div>
                            <img
                                :src="`/storage/${img}`"
                                :alt="`Фото ${i + 1}`"
                                @load="$event.target.classList.add('--loaded')"
                                @error="$event.target.style.display = 'none'"
                            />
                        </div>
                    </div>
                </div>

                <!-- Информация -->
                <div class="product-detail__info">
                    <h2 class="product-detail__name">{{ product.name }}</h2>
                    <span class="product-detail__price">{{ Number(product.price).toLocaleString('ru-RU') }} &#8381;</span>

                    <div class="product-detail__block" v-if="product.composition">
                        <h6 class="product-detail__block-title">Состав</h6>
                        <p class="product-detail__text">{{ product.composition }}</p>
                    </div>

                    <div class="product-detail__block" v-if="product.description">
                        <h6 class="product-detail__block-title">Описание</h6>
                        <p class="product-detail__text">{{ product.description }}</p>
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
                                <button class="product-detail__counter-btn" @click="decrementItem(product.id)">-</button>
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
$parchment: #f4ead9;
$ink: #1c1114;
$wine: #7a1220;
$gold: #c9a15a;

.product-detail {
    background: $parchment;
    padding: 50px 40px 60px;
    color: $ink;
    flex: 1;

    @media (max-width: 768px) {
        padding: 32px 16px;
    }

    &__content {
        display: flex;
        gap: 40px;

        @media (max-width: 768px) {
            flex-direction: column;
        }
    }

    &__gallery {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    &__main-image {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 3 / 4;
    }

    &__image-placeholder {
        position: absolute;
        inset: 0;
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
        font-size: 26px;
        color: #fff;
        z-index: 3;
        padding: 8px 16px;
        transition: opacity 0.3s ease;

        &:hover {
            opacity: 0.7;
        }

        &.--prev { left: 0; }
        &.--next { right: 0; }
    }

    &__thumbs {
        display: flex;
        gap: 10px;
    }

    &__thumb {
        width: 56px;
        height: 56px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        opacity: 0.6;
        cursor: pointer;
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

        &-bg {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        &.--active {
            opacity: 1;
            outline: 2px solid $gold;
        }

        &:hover {
            opacity: 0.8;
        }
    }

    &__info {
        flex: 1;
    }

    &__name {
        font-size: 32px;
        margin-bottom: 10px;
        color: $ink;
    }

    &__price {
        font-size: 24px;
        font-weight: 600;
        display: block;
        margin-bottom: 18px;
        color: $wine;
    }

    &__block {
        margin-bottom: 16px;
    }

    &__block-title {
        font-family: 'Work Sans', sans-serif;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8a7c70;
        margin: 18px 0 6px;
    }

    &__text {
        font-size: 14px;
        line-height: 1.6;
        color: #4d423a;
    }

    &__actions {
        margin-top: 20px;
    }

    &__cart-control {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    &__counter {
        display: flex;
        align-items: center;
        border: 2px solid $wine;
        border-radius: 30px;
        overflow: hidden;
    }

    &__counter-btn {
        width: 44px;
        height: 44px;
        font-size: 20px;
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
        width: 44px;
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        color: $wine;
    }

    &__in-cart {
        font-size: 14px;
        font-weight: 500;
        color: $gold;
    }
}
</style>
