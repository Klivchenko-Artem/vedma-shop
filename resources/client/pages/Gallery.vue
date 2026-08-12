<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

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

const gradients = [
    'linear-gradient(135deg, #7a1220, #6b4c82)',
    'linear-gradient(135deg, #4f0a12, #c9a15a)',
    'linear-gradient(135deg, #6b4c82, #7a1220)',
    'linear-gradient(135deg, #c9a15a, #4f0a12)',
];

function getGradient(i) {
    return gradients[i % gradients.length];
}
</script>

<template>
    <Head>
        <title>Галерея работ — фото букетов | Ведьмина метла, Таганрог</title>
        <meta name="description" content="Фото букетов и композиций от цветочного салона «Ведьмина метла» в Таганроге. Смотрите наши работы." />
    </Head>
    <div class="gallery-page section">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow" style="color: #7a1220;">наши работы</span>
                <h3 class="gallery-page__title">Галерея</h3>
            </div>

            <div class="gallery-page__grid">
                <div
                    v-for="(img, i) in images"
                    :key="img.id || i"
                    class="gallery-page__item fade-in"
                    :style="{ animationDelay: `${i * 0.05}s` }"
                    @click="openModal(i)"
                >
                    <div class="gallery-page__image">
                        <div class="gallery-page__placeholder" :style="{ background: getGradient(i) }">
                            <span class="gallery-page__emoji">&#x1F490;</span>
                        </div>
                        <img
                            v-if="imageUrl(img.image)"
                            :src="imageUrl(img.image)"
                            :alt="img.title || 'Фото'"
                            class="gallery-page__img"
                            @load="$event.target.classList.add('--loaded')"
                            @error="$event.target.style.display = 'none'"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно -->
        <Transition name="modal">
            <div v-if="isModalOpen" class="gallery-modal" @click.self="closeModal">
                <div class="gallery-modal__content">
                    <span class="gallery-modal__close" @click="closeModal">&#x2715;</span>

                    <div class="gallery-modal__image">
                        <div class="gallery-modal__placeholder" :style="{ background: getGradient(modalIndex) }">
                            <span>&#x1F490;</span>
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

                    <div class="gallery-modal__nav">
                        <button class="gallery-modal__arrow" @click="prevImage(images.length)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"/>
                            </svg>
                        </button>
                        <span class="gallery-modal__counter">{{ modalIndex + 1 }} / {{ images.length }}</span>
                        <button class="gallery-modal__arrow" @click="nextImage(images.length)">
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
$parchment: #f4ead9;
$ink: #1c1114;
$wine: #7a1220;
$gold: #c9a15a;

.gallery-page {
    background: $parchment;
    padding: 60px 40px;
    color: $ink;
    flex: 1;

    @media (max-width: 768px) {
        padding: 32px 16px;
    }

    &__title {
        font-size: clamp(28px, 4vw, 34px);
        color: $ink;
        text-align: center;
    }

    &__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-top: 30px;

        @media (max-width: 900px) {
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
        border-radius: 10px;
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

    &__emoji {
        font-size: 48px;
        color: #fff;
    }

    &__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 2;

        &.--loaded {
            opacity: 1;
        }
    }
}

.gallery-modal {
    position: fixed;
    inset: 0;
    z-index: 300;
    background: rgba(10, 6, 7, 0.94);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;

    &__content {
        position: relative;
        max-width: 680px;
        width: 100%;
        text-align: center;
    }

    &__close {
        position: absolute;
        top: -40px;
        right: 0;
        font-size: 22px;
        color: #fff;
        cursor: pointer;
        z-index: 10;
        transition: opacity 0.3s ease;

        &:hover {
            opacity: 0.7;
        }
    }

    &__image {
        position: relative;
        width: 100%;
        max-width: 680px;
        aspect-ratio: 1;
        margin: 0 auto;
        border-radius: 10px;
        overflow: hidden;

        @media (max-width: 480px) {
            max-width: 100%;
        }
    }

    &__placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        z-index: 1;
    }

    &__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 2;

        &.--loaded {
            opacity: 1;
        }
    }

    &__nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        margin-top: 20px;
    }

    &__arrow {
        width: 44px;
        height: 44px;
        border: 1px solid $gold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: $gold;
        cursor: pointer;
        transition: all 0.3s ease;

        &:hover {
            background: $gold;
            color: #150d10;
        }
    }

    &__counter {
        color: rgba(255, 255, 255, 0.5);
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
