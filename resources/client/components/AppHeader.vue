<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/stores/cart';
import BurgerMenu from '@/components/BurgerMenu.vue';

const emit = defineEmits(['open-cart']);
const { totalCount } = useCart();

const isBurgerOpen = ref(false);

const navLinks = [
    { text: 'Главная', href: '/' },
    { text: 'Каталог', href: '/catalog' },
    { text: 'Галерея', href: '/gallery' },
    { text: 'Контакты', href: '/#contacts' },
];

const leftLinks = [
    { text: 'Главная', href: '/' },
    { text: 'Каталог', href: '/catalog' },
];

const rightLinks = [
    { text: 'Галерея', href: '/gallery' },
    { text: 'Контакты', href: '/#contacts' },
];
</script>

<template>
    <header class="header">
        <div class="header__inner">
            <!-- Навигация слева -->
            <nav class="header__nav --left">
                <Link
                    v-for="link in leftLinks"
                    :key="link.href"
                    :href="link.href"
                    class="header__link"
                >
                    {{ link.text }}
                </Link>
            </nav>

            <!-- Бургер (только мобилка, занимает место лев. навигации) -->
            <button
                class="header__burger"
                :class="{ '--active': isBurgerOpen }"
                @click="isBurgerOpen = !isBurgerOpen"
                aria-label="Меню"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Логотип (по центру) -->
            <Link href="/" class="header__logo">
                <span class="header__logo-icon">🌿</span>
                <span class="header__logo-text">Ведьмина метла</span>
            </Link>

            <!-- Навигация справа -->
            <nav class="header__nav --right">
                <Link
                    v-for="link in rightLinks"
                    :key="link.href"
                    :href="link.href"
                    class="header__link"
                >
                    {{ link.text }}
                </Link>
            </nav>
        </div>

        <!-- Корзина — абсолютно поверх, не влияет на грид -->
        <button class="header__cart" @click="emit('open-cart')" aria-label="Корзина">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            <span v-if="totalCount > 0" class="header__cart-badge">{{ totalCount }}</span>
        </button>

        <!-- Мобильное бургер-меню -->
        <BurgerMenu
            :is-open="isBurgerOpen"
            :links="navLinks"
            @close="isBurgerOpen = false"
            @open-cart="emit('open-cart'); isBurgerOpen = false"
        />
    </header>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-bg: #fafafa;
$color-white: #ffffff;

.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    background: rgba($color-white, 0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);

    &__inner {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 clamp(16px, 3vw, 40px);
        height: 72px;

        @media (max-width: 768px) {
            grid-template-columns: 48px 1fr 48px;
        }
    }

    // Бургер — только мобилка
    &__burger {
        display: none;
        flex-direction: column;
        gap: 5px;
        width: 28px;
        padding: 4px 0;
        background: none;
        border: none;
        cursor: pointer;
        z-index: 101;

        span {
            display: block;
            width: 100%;
            height: 2px;
            background: $color-primary;
            border-radius: 1px;
            transition: all 0.3s ease;
        }

        &.--active {
            span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
            span:nth-child(2) { opacity: 0; }
            span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
        }

        @media (max-width: 768px) {
            display: flex;
        }
    }

    // Навигация — только десктоп
    &__nav {
        display: flex;
        gap: clamp(16px, 2.5vw, 40px);

        &.--left {
            justify-content: flex-end;
        }

        &.--right {
            justify-content: flex-start;
        }

        @media (max-width: 768px) {
            display: none;
        }
    }

    &__link {
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: $color-primary;
        transition: color 0.3s ease;
        position: relative;

        &::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: $color-accent;
            transition: width 0.3s ease;
        }

        &:hover {
            color: $color-accent;
            &::after { width: 100%; }
        }
    }

    // Логотип — всегда по центру
    &__logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 clamp(16px, 3vw, 60px);
        white-space: nowrap;
    }

    &__logo-icon {
        font-size: 22px;
    }

    &__logo-text {
        font-size: clamp(14px, 2vw, 18px);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: $color-primary;
    }

    // Корзина — поверх хедера, не ломает грид
    &__cart {
        position: absolute;
        top: 50%;
        right: clamp(16px, 3vw, 40px);
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        color: $color-primary;
        transition: color 0.3s ease;
        z-index: 101;

        &:hover {
            color: $color-accent;
        }
    }

    &__cart-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        font-size: 10px;
        font-weight: 700;
        color: $color-white;
        background: $color-accent;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
}
</style>
