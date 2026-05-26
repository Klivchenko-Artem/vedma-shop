<script setup>
import { watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    isOpen: Boolean,
    links: Array,
});

const emit = defineEmits(['close', 'open-cart']);

// Блокируем скролл страницы при открытом меню
watch(() => props.isOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
});
</script>

<template>
    <Transition name="burger-menu">
        <div v-if="isOpen" class="burger-menu">
            <div class="burger-menu__overlay" @click="emit('close')" />
            <div class="burger-menu__content">
                <nav class="burger-menu__nav">
                    <Link
                        v-for="link in links"
                        :key="link.href"
                        :href="link.href"
                        class="burger-menu__link"
                        @click="emit('close')"
                    >
                        {{ link.text }}
                    </Link>
                </nav>

                <div class="burger-menu__bottom">
                    <button class="burger-menu__cart-btn" @click="emit('open-cart')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 01-8 0"/>
                        </svg>
                        Корзина
                    </button>

                    <div class="burger-menu__contacts">
                        <a href="tel:+79991234567" class="burger-menu__contact">+7 (999) 123-45-67</a>
                        <a href="mailto:info@vedma.ru" class="burger-menu__contact">info@vedma.ru</a>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-white: #ffffff;

.burger-menu {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    height: 100dvh;
    z-index: 99;

    &__overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
    }

    &__content {
        position: absolute;
        top: 0;
        left: 0;
        width: 280px;
        height: 100%;
        background: $color-white;
        padding: 88px 24px 32px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }

    &__nav {
        display: flex;
        flex-direction: column;
    }

    &__link {
        display: block;
        padding: 16px 0;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: $color-primary;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        transition: color 0.3s ease;
        text-align: left;
        width: 100%;

        &:hover {
            color: $color-accent;
        }
    }

    &__contacts {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: auto;
        padding-top: 24px;
    }

    &__contact {
        font-size: 12px;
        color: #777;
        transition: color 0.3s ease;

        &:hover {
            color: $color-primary;
        }
    }
}

.burger-menu-enter-active,
.burger-menu-leave-active {
    transition: opacity 0.3s ease;

    .burger-menu__content {
        transition: transform 0.3s ease;
    }
}

.burger-menu-enter-from,
.burger-menu-leave-to {
    opacity: 0;

    .burger-menu__content {
        transform: translateX(-100%);
    }
}
</style>
