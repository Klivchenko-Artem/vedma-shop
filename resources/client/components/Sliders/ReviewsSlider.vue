<script setup>
import { ref } from 'vue';

defineProps({
    reviews: Array,
});

const currentIndex = ref(0);

function next(total) {
    currentIndex.value = (currentIndex.value + 1) % total;
}

function prev(total) {
    currentIndex.value = (currentIndex.value - 1 + total) % total;
}
</script>

<template>
    <div class="reviews-slider" v-if="reviews && reviews.length">
        <div class="reviews-slider__track">
            <Transition name="review-slide" mode="out-in">
                <div class="review-card" :key="currentIndex">
                    <p class="review-card__text">&laquo;{{ reviews[currentIndex].text }}&raquo;</p>
                    <div class="review-card__author">
                        <span class="review-card__name">&#8212; {{ reviews[currentIndex].author }}</span>
                    </div>
                </div>
            </Transition>
        </div>

        <div class="reviews-slider__controls">
            <span class="reviews-slider__arrow" @click="prev(reviews.length)">&lsaquo;</span>
            <span class="reviews-slider__arrow" @click="next(reviews.length)">&rsaquo;</span>
        </div>
    </div>
</template>

<style lang="scss">
$parchment: #f4ead9;
$gold: #c9a15a;

.reviews-slider {
    max-width: 560px;
    margin: 0 auto;
    text-align: center;

    &__track {
        position: relative;
        overflow: hidden;
        min-height: 140px;
        display: flex;
        align-items: center;
    }

    &__controls {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 20px;
        color: $gold;
    }

    &__arrow {
        width: 44px;
        height: 44px;
        border: 1px solid $gold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        user-select: none;
        transition: all 0.3s ease;

        &:hover {
            background: $gold;
            color: #150d10;
        }
    }
}

.review-card {
    text-align: center;
    padding: 20px;
    width: 100%;

    &__text {
        font-family: 'Cormorant Garamond', serif;
        font-size: 22px;
        font-style: italic;
        color: $parchment;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    &__author {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    &__name {
        font-size: 14px;
        color: $gold;
    }
}

.review-slide-enter-active,
.review-slide-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.review-slide-enter-from {
    opacity: 0;
    transform: translateX(20px);
}

.review-slide-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}
</style>
