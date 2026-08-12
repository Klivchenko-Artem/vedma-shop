<script setup>
import { watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    isOpen: Boolean,
    links: Array,
});

const emit = defineEmits(['close', 'open-cart']);

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
                </div>
            </div>
        </div>
    </Transition>
</template>

<style lang="scss">
$night: #150d10;
$parchment: #f4ead9;
$parchment-dim: #e9dcc4;
$gold: #c9a15a;
$wine: #7a1220;

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
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
    }

    &__content {
        position: absolute;
        top: 0;
        left: 0;
        width: 280px;
        height: 100%;
        background: $night;
        padding: 88px 24px 32px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
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
        font-weight: 400;
        letter-spacing: 0.03em;
        color: $parchment-dim;
        border-bottom: 1px solid #33232a;
        transition: color 0.3s ease;

        &:hover {
            color: $gold;
        }
    }

    &__bottom {
        padding-top: 24px;
    }

    &__cart-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        color: $gold;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.3s ease;

        &:hover {
            color: $parchment;
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
