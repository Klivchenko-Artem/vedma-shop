<script setup>
import { ref } from 'vue';

defineProps({
    images: Array,
});

function imageUrl(path) {
    if (!path || path.startsWith('/')) return null;
    return `/storage/${path}`;
}

const isModalOpen = ref(false);
const modalIndex = ref(0);

function openModal(index) {
    modalIndex.value = index;
    isModalOpen.value = true;
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    isModalOpen.value = false;
    document.body.style.overflow = '';
}

function nextImage(total) {
    modalIndex.value = (modalIndex.value + 1) % total;
}

function prevImage(total) {
    modalIndex.value = (modalIndex.value - 1 + total) % total;
}

function getColor(i) {
    const colors = ['#e8ddd5', '#d5dde8', '#dde8d5', '#e8d5dd', '#d5e8dd', '#e0d8e8', '#ddd5e8', '#e8e5d5', '#d5e8e0', '#e8d5d5', '#e0e8d5', '#d8e0e8'];
    return colors[i % colors.length];
}
</script>

<template>
    <div class="gallery-page section">
        <div class="container">
            <h1 class="section-title">Галерея</h1>
            <p class="section-subtitle">Наши лучшие работы и авторские композиции</p>

            <div class="gallery-page__grid">
                <div
                    v-for="(img, i) in images"
                    :key="img.id || i"
                    class="gallery-page__item fade-in"
                    :style="{ animationDelay: `${i * 0.05}s` }"
                    @click="openModal(i)"
                >
                    <div class="gallery-page__image">
                        <div class="gallery-page__placeholder" :style="{ backgroundColor: getColor(i) }">
                            <span class="gallery-page__emoji">🌺</span>
                        </div>
                        <img
                            v-if="imageUrl(img.image)"
                            :src="imageUrl(img.image)"
                            :alt="img.title || 'Фото'"
                            class="gallery-page__img"
                            @load="$event.target.classList.add('--loaded')"
                            @error="$event.target.style.display = 'none'"
                        />
                        <div class="gallery-page__overlay">
                            <span class="gallery-page__zoom">🔍</span>
                        </div>
                    </div>
                    <span v-if="img.title" class="gallery-page__caption">{{ img.title }}</span>
                </div>
            </div>
        </div>

        <!-- Модальное окно -->
        <Transition name="modal">
            <div v-if="isModalOpen" class="gallery-modal" @click.self="closeModal">
                <div class="gallery-modal__content">
                    <button class="gallery-modal__close" @click="closeModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>

                    <div class="gallery-modal__image">
                        <div class="gallery-modal__placeholder" :style="{ backgroundColor: getColor(modalIndex) }">
                            <span>🌺</span>
                        </div>
                        <img
                            v-if="imageUrl(images[modalIndex]?.image)"
                            :key="modalIndex"
                            :src="imageUrl(images[modalIndex].image)"
                            :alt="images[modalIndex]?.title || 'Фото'"
                            class="gallery-modal__img"
                            @load="$event.target.classList.add('--loaded')"
                            @error="$event.target.style.display = 'none'"
                        />
                    </div>

                    <p v-if="images[modalIndex]?.title" class="gallery-modal__title">
                        {{ images[modalIndex].title }}
                    </p>

                    <div class="gallery-modal__nav">
                        <button @click="prevImage(images.length)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"/>
                            </svg>
                        </button>
                        <span class="gallery-modal__counter">{{ modalIndex + 1 }} / {{ images.length }}</span>
                        <button @click="nextImage(images.length)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-white: #ffffff;

.gallery-page {
    &__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;

        @media (max-width: 1024px) {
            grid-template-columns: repeat(3, 1fr);
        }

        @media (max-width: 768px) {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }

    &__item {
        cursor: pointer;
    }

    &__image {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.4s ease;

        .gallery-page__item:hover & {
            transform: scale(1.02);
        }
    }

    &__placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    &__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 2;

        &.--loaded {
            opacity: 1;
        }
    }

    &__emoji {
        font-size: 48px;
        opacity: 0.5;
    }

    &__overlay {
        position: absolute;
        inset: 0;
        background: rgba($color-primary, 0);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
        z-index: 3;

        .gallery-page__item:hover & {
            background: rgba($color-primary, 0.3);
        }
    }

    &__zoom {
        font-size: 28px;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.3s ease;

        .gallery-page__item:hover & {
            opacity: 1;
            transform: scale(1);
        }
    }

    &__caption {
        display: block;
        font-size: 13px;
        color: #777;
        margin-top: 8px;
        text-align: center;
    }
}

.gallery-modal {
    position: fixed;
    inset: 0;
    z-index: 300;
    background: rgba(0, 0, 0, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;

    &__content {
        position: relative;
        max-width: 700px;
        width: 100%;
    }

    &__close {
        position: absolute;
        top: -48px;
        right: 0;
        color: rgba($color-white, 0.6);
        transition: color 0.3s ease;
        z-index: 10;

        &:hover { color: $color-white; }
    }

    &__image {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }

    &__placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        opacity: 0.6;
        z-index: 1;
    }

    &__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 2;

        &.--loaded {
            opacity: 1;
        }
    }

    &__title {
        text-align: center;
        color: rgba($color-white, 0.7);
        font-size: 16px;
        margin-top: 16px;
    }

    &__nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        margin-top: 16px;

        button {
            color: rgba($color-white, 0.5);
            transition: color 0.3s ease;
            &:hover { color: $color-white; }
        }
    }

    &__counter {
        color: rgba($color-white, 0.5);
        font-size: 14px;
    }
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
