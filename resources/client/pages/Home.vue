<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import ProductCard from '@/components/UI/ProductCard.vue';
import ReviewsSlider from '@/components/Sliders/ReviewsSlider.vue';
import witchFlowers from '@/assets/images/witch-flowers.png';
import broomIcon from '@/assets/images/broom.png';
import cauldronIcon from '@/assets/images/cauldron.png';
import spellbookIcon from '@/assets/images/spellbook.png';

const props = defineProps({
    popularProducts: Array,
    reviews: Array,
    featureDescs: Array,
});

const features = [
    { icon: broomIcon, title: 'Всегда свежие' },
    { icon: broomIcon, title: 'Доставка 24ч' },
    { icon: cauldronIcon, title: 'Ручная сборка' },
    { icon: spellbookIcon, title: 'Гарантия' },
];

const defaultDescs = [
    'Закупаем цветы каждое утро у лучших поставщиков',
    'Привезём в любую точку города в течение суток',
    'Каждый букет собирает флорист вручную',
    'Не понравилось — заменим бесплатно',
];
</script>

<template>
    <Head>
        <title>Ведьмина метла — цветы и букеты в Таганроге с доставкой</title>
        <meta name="description" content="Цветочный салон «Ведьмина метла» в Таганроге. Свежие букеты ручной сборки, доставка за 24 часа. Закажите цветы с доставкой по Таганрогу." />
    </Head>
    <div class="home">
        <!-- Hero -->
        <section class="hero">
            <div class="hero__glow"></div>
            <div class="hero__text">
                <span class="eyebrow fade-in">цветочный салон с характером</span>
                <h2 class="hero__title fade-in" style="animation-delay: 0.1s">
                    Букеты,<br>заговорённые<br>на удачу
                </h2>
                <p class="hero__subtitle fade-in" style="animation-delay: 0.2s">
                    Собираем вручную, доставляем за 24 часа и добавляем немного тёмной магии в каждый лепесток.
                </p>
                <a href="/catalog" class="btn btn--primary hero__cta fade-in" style="animation-delay: 0.3s">
                    Смотреть каталог
                </a>
            </div>
            <div class="hero__img fade-in" style="animation-delay: 0.2s">
                <img :src="witchFlowers" alt="ведьма с цветами" />
            </div>
        </section>

        <!-- Почему мы -->
        <section class="features">
            <div class="container">
                <div class="features__head">
                    <span class="eyebrow">почему мы</span>
                    <h3>Четыре причины довериться нам</h3>
                </div>
                <div class="features__grid">
                    <div
                        v-for="(feature, i) in features"
                        :key="i"
                        class="feat-card fade-in"
                        :style="{ animationDelay: `${i * 0.1}s` }"
                    >
                        <img :src="feature.icon" :alt="feature.title" class="feat-card__icon" />
                        <h4 class="feat-card__title">{{ feature.title }}</h4>
                        <p class="feat-card__desc">{{ featureDescs?.[i] || defaultDescs[i] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Популярные букеты -->
        <section class="popular section">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">самые востребованные</span>
                    <h3 class="section-title">Популярные букеты</h3>
                </div>
                <div class="popular__grid">
                    <ProductCard
                        v-for="product in popularProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
                <div class="popular__more">
                    <a href="/catalog" class="btn btn--secondary">Смотреть весь каталог</a>
                </div>
            </div>
        </section>

        <!-- Отзывы -->
        <section class="reviews-section section">
            <div class="container">
                <span class="eyebrow" style="display:block;text-align:center;">отзывы</span>
                <h3 class="section-title">Отзывы клиентов</h3>
                <ReviewsSlider :reviews="reviews" />
            </div>
        </section>
    </div>
</template>

<style lang="scss">
$night: #150d10;
$wine-deep: #4f0a12;
$parchment: #f4ead9;
$parchment-dim: #e9dcc4;
$gold: #c9a15a;
$ink: #1c1114;

// Hero
.hero {
    position: relative;
    display: flex;
    background: linear-gradient(120deg, $night 45%, $wine-deep 100%);
    min-height: 460px;
    overflow: hidden;

    @media (max-width: 768px) {
        flex-direction: column;
        min-height: auto;
    }

    &__glow {
        position: absolute;
        right: 120px;
        top: 50%;
        transform: translateY(-50%);
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba($gold, 0.28), transparent 70%);
        pointer-events: none;

        @media (max-width: 768px) {
            right: -60px;
            top: auto;
            bottom: -100px;
        }
    }

    &__text {
        flex: 1;
        padding: 70px 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 18px;
        z-index: 2;

        @media (max-width: 768px) {
            padding: 40px 24px;
            align-items: center;
            text-align: center;
        }
    }

    &__title {
        font-size: clamp(36px, 5vw, 50px);
        line-height: 1.1;
        color: $parchment;
    }

    &__subtitle {
        color: #cbb9ae;
        max-width: 420px;
        font-size: 15px;
        line-height: 1.6;
    }

    &__cta {
        align-self: flex-start;

        @media (max-width: 768px) {
            align-self: center;
        }
    }

    &__img {
        flex: 1;
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        z-index: 2;

        img {
            max-height: 460px;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.5));

            @media (max-width: 768px) {
                max-height: 300px;
            }
        }
    }
}

// Фичи
.features {
    background: $parchment;
    padding: 60px 40px;
    color: $ink;

    @media (max-width: 768px) {
        padding: 40px 16px;
    }

    &__head {
        text-align: center;
        margin-bottom: 36px;

        h3 {
            font-size: clamp(24px, 3vw, 32px);
            color: $ink;
        }
    }

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;

        @media (max-width: 900px) {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }
}

.feat-card {
    background: #fff;
    border: 1px solid #e4d6bd;
    border-radius: 14px;
    padding: 26px 20px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    &__icon {
        height: 56px;
        margin: 0 auto 14px;
    }

    &__title {
        font-size: 20px;
        margin-bottom: 8px;
        color: $ink;
    }

    &__desc {
        font-size: 13px;
        color: #6b5f56;
        line-height: 1.5;
    }
}

// Популярные
.popular {
    background: $night;

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 36px;

        @media (max-width: 900px) {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }

    &__more {
        text-align: center;
        margin-top: 34px;
    }
}

.section-head {
    text-align: center;

    .eyebrow {
        display: block;
        margin-bottom: 4px;
    }
}

// Отзывы
.reviews-section {
    background: $wine-deep;
}
</style>
