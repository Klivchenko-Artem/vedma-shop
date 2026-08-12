<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/stores/cart';
import BurgerMenu from '@/components/BurgerMenu.vue';
import logoWide from '@/assets/images/logo-wide.png';

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

            <Link href="/" class="header__logo">
                <img :src="logoWide" alt="Ведьмина метла" class="header__logo-img" />
            </Link>

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

        <button class="header__cart" @click="emit('open-cart')" aria-label="Корзина">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            <span v-if="totalCount > 0" class="header__cart-badge">{{ totalCount }}</span>
        </button>

        <BurgerMenu
            :is-open="isBurgerOpen"
            :links="navLinks"
            @close="isBurgerOpen = false"
            @open-cart="emit('open-cart'); isBurgerOpen = false"
        />
    </header>
</template>

<style lang="scss">
$night: #150d10;
$parchment-dim: #e9dcc4;
$gold: #c9a15a;

.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    background: $night;
    border-bottom: 1px solid #33232a;

    &__inner {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 clamp(16px, 3vw, 40px);
        height: 72px;

        @media (max-width: 768px) {
            grid-template-columns: 48px 1fr 48px;
        }
    }

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
            background: $parchment-dim;
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

    &__nav {
        display: flex;
        gap: clamp(16px, 2.5vw, 28px);

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
        font-size: 18px;
        font-weight: 400;
        letter-spacing: 0.03em;
        color: $parchment-dim;
        transition: color 0.3s ease;
        position: relative;

        &::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: $gold;
            transition: width 0.3s ease;
        }

        &:hover {
            color: $gold;
            &::after { width: 100%; }
        }
    }

    &__logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 clamp(16px, 3vw, 40px);
    }

    &__logo-img {
        height: 56px;
        width: auto;
    }

    &__cart {
        position: absolute;
        top: 50%;
        right: clamp(16px, 3vw, 40px);
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        color: $parchment-dim;
        transition: color 0.3s ease;
        z-index: 101;

        &:hover {
            color: $gold;
        }
    }

    &__cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        font-size: 10px;
        font-weight: 600;
        color: #fff;
        background: #7a1220;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
}
</style>
