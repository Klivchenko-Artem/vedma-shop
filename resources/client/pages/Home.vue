<script setup>
import { ref } from 'vue';
import ProductCard from '@/components/UI/ProductCard.vue';
import ReviewsSlider from '@/components/Sliders/ReviewsSlider.vue';

defineProps({
    popularProducts: Array,
    reviews: Array,
});

const features = ref([
    {
        icon: '🌿',
        title: 'Всегда свежие',
        desc: 'Цветы поступают от лучших поставщиков каждый день',
    },
    {
        icon: '🚚',
        title: 'Доставка 24ч',
        desc: 'Доставим букет в любую точку города в течение суток',
    },
    {
        icon: '✋',
        title: 'Ручная сборка',
        desc: 'Каждый букет создаётся вручную нашими флористами',
    },
    {
        icon: '💚',
        title: 'Гарантия',
        desc: 'Если букет не понравится — заменим или вернём деньги',
    },
]);
</script>

<template>
    <div class="home">
        <!-- Hero -->
        <section class="hero">
            <div class="hero__bg">
                <div class="hero__decoration hero__decoration--left"></div>
                <div class="hero__decoration hero__decoration--right"></div>
            </div>
            <div class="hero__content container">
                <span class="hero__badge fade-in">Цветочный магазин</span>
                <h1 class="hero__title fade-in" style="animation-delay: 0.1s">
                    Ведьмина<br>метла
                </h1>
                <p class="hero__subtitle fade-in" style="animation-delay: 0.2s">
                    Магия живых цветов для вашего дома.
                    Авторские букеты, ручная сборка, доставка 24/7.
                </p>
                <a href="/catalog" class="btn btn--primary hero__cta fade-in" style="animation-delay: 0.3s">
                    Выбрать букет
                </a>
            </div>
        </section>

        <!-- Почему мы -->
        <section class="features section">
            <div class="container">
                <h2 class="section-title">Почему мы</h2>
                <div class="features__grid">
                    <div
                        v-for="(feature, i) in features"
                        :key="i"
                        class="feature-card fade-in"
                        :style="{ animationDelay: `${i * 0.1}s` }"
                    >
                        <span class="feature-card__icon">{{ feature.icon }}</span>
                        <h3 class="feature-card__title">{{ feature.title }}</h3>
                        <p class="feature-card__desc">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Популярные товары -->
        <section class="popular section magic-line">
            <div class="container">
                <h2 class="section-title">Популярные букеты</h2>
                <p class="section-subtitle">Самые востребованные композиции нашей студии</p>
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
                <h2 class="section-title">Отзывы клиентов</h2>
                <ReviewsSlider :reviews="reviews" />
            </div>
        </section>
    </div>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-white: #ffffff;
$color-bg: #fafafa;

// Hero
.hero {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f0ebe5 0%, #e5ebe5 50%, #f0f0ea 100%);
    overflow: hidden;

    &__bg {
        position: absolute;
        inset: 0;
    }

    &__decoration {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        opacity: 0.08;

        &--left {
            bottom: -100px;
            left: -100px;
            background: $color-primary;
            width: 400px;
            height: 400px;
        }

        &--right {
            top: -50px;
            right: -50px;
            background: $color-accent;
            width: 350px;
            height: 350px;
        }
    }

    &__content {
        position: relative;
        z-index: 1;
        text-align: center;
        padding: clamp(60px, 10vw, 120px) 0;
    }

    &__badge {
        display: inline-block;
        padding: 6px 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: $color-primary;
        border: 1px solid rgba($color-primary, 0.3);
        border-radius: 20px;
        margin-bottom: 24px;
    }

    &__title {
        font-size: clamp(40px, 8vw, 80px);
        font-weight: 700;
        line-height: 1.05;
        color: $color-primary;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    &__subtitle {
        font-size: clamp(14px, 2vw, 18px);
        color: #666;
        max-width: 480px;
        margin: 0 auto 32px;
        line-height: 1.6;
    }

    &__cta {
        padding: 14px 40px;
        font-size: 14px;
    }
}

// Фичи
.features__grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;

    @media (max-width: 768px) {
        grid-template-columns: repeat(2, 1fr);
    }

    @media (max-width: 480px) {
        grid-template-columns: 1fr;
    }
}

.feature-card {
    text-align: center;
    padding: 32px 20px;
    background: $color-white;
    border-radius: 8px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
    }

    &__icon {
        display: block;
        font-size: 36px;
        margin-bottom: 16px;
    }

    &__title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2c2c2c;
    }

    &__desc {
        font-size: 13px;
        color: #999;
        line-height: 1.5;
    }
}

// Популярные
.popular {
    background: $color-bg;

    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;

        @media (max-width: 1024px) {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }

    &__more {
        text-align: center;
        margin-top: 40px;
    }
}

// Отзывы
.reviews-section {
    background: $color-white;
}
</style>
