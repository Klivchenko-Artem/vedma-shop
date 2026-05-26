<script setup>
import AppHeader from '@/components/AppHeader.vue';
import AppFooter from '@/components/AppFooter.vue';
import CartDrawer from '@/components/Modals/CartDrawer.vue';
import { ref } from 'vue';

const isCartOpen = ref(false);

function openCart() {
    isCartOpen.value = true;
}

function closeCart() {
    isCartOpen.value = false;
}
</script>

<template>
    <div class="layout">
        <AppHeader @open-cart="openCart" />

        <main class="layout__content">
            <slot />
        </main>

        <AppFooter />

        <CartDrawer :is-open="isCartOpen" @close="closeCart" />
    </div>
</template>

<style lang="scss">
.layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;

    &__content {
        flex: 1;
        padding-top: 72px; // высота фиксированного хедера
    }
}
</style>
