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
                    <div class="review-card__stars">
                        <span v-for="s in reviews[currentIndex].rating" :key="s">★</span>
                    </div>
                    <p class="review-card__text">«{{ reviews[currentIndex].text }}»</p>
                    <div class="review-card__author">
                        <span class="review-card__avatar">{{ reviews[currentIndex].author.charAt(0) }}</span>
                        <span class="review-card__name">{{ reviews[currentIndex].author }}</span>
                    </div>
                </div>
            </Transition>
        </div>

        <div class="reviews-slider__controls">
            <button class="reviews-slider__btn" @click="prev(reviews.length)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>

            <div class="reviews-slider__dots">
                <button
                    v-for="(_, i) in reviews"
                    :key="i"
                    class="reviews-slider__dot"
                    :class="{ '--active': i === currentIndex }"
                    @click="currentIndex = i"
                />
            </div>

            <button class="reviews-slider__btn" @click="next(reviews.length)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;

.reviews-slider {
    max-width: 700px;
    margin: 0 auto;

    &__track {
        position: relative;
        overflow: hidden;
        min-height: 180px;
        display: flex;
        align-items: center;
    }

    &__controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 24px;
    }

    &__btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 50%;
        color: #999;
        transition: all 0.3s ease;

        &:hover {
            border-color: $color-primary;
            color: $color-primary;
        }
    }

    &__dots {
        display: flex;
        gap: 8px;
    }

    &__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ddd;
        transition: all 0.3s ease;

        &.--active {
            background: $color-accent;
            transform: scale(1.3);
        }
    }
}

.review-card {
    text-align: center;
    padding: 20px;
    width: 100%;

    &__stars {
        color: #f4c542;
        font-size: 18px;
        margin-bottom: 16px;
        letter-spacing: 2px;
    }

    &__text {
        font-size: clamp(15px, 2vw, 18px);
        line-height: 1.7;
        color: #555;
        font-style: italic;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    &__author {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    &__avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: $color-primary;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    &__name {
        font-size: 14px;
        font-weight: 600;
        color: $color-primary;
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
