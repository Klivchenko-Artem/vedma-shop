<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const contacts = computed(() => page.props.footer?.contacts || {});
const socials = computed(() => page.props.footer?.socials || {});
</script>

<template>
    <footer class="footer">
        <div class="footer__inner">
            <div class="footer__top">
                <div class="footer__brand">
                    <span class="footer__logo-icon">🌿</span>
                    <span class="footer__logo-text">Ведьмина метла</span>
                    <p class="footer__tagline">Магия живых цветов</p>
                </div>

                <nav class="footer__nav">
                    <h4 class="footer__nav-title">Навигация</h4>
                    <Link href="/" class="footer__link">Главная</Link>
                    <Link href="/catalog" class="footer__link">Каталог</Link>
                    <Link href="/gallery" class="footer__link">Галерея</Link>
                </nav>

                <div class="footer__contacts">
                    <h4 class="footer__nav-title">Контакты</h4>
                    <a v-if="contacts.phone" :href="`tel:${contacts.phone.replace(/[^+\d]/g, '')}`" class="footer__link">
                        {{ contacts.phone }}
                    </a>
                    <a v-if="contacts.email" :href="`mailto:${contacts.email}`" class="footer__link">
                        {{ contacts.email }}
                    </a>
                    <p v-if="contacts.address" class="footer__link --address">
                        {{ contacts.address }}
                    </p>
                </div>

                <div class="footer__socials" v-if="socials.telegram_enabled || socials.vk_enabled || socials.instagram_enabled || socials.whatsapp_enabled">
                    <h4 class="footer__nav-title">Мы в соцсетях</h4>
                    <div class="footer__social-links">
                        <!-- Telegram -->
                        <a v-if="socials.telegram_enabled" :href="socials.telegram_url || '#'" target="_blank" rel="noopener" class="footer__social" aria-label="Telegram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                            </svg>
                        </a>
                        <!-- VK -->
                        <a v-if="socials.vk_enabled" :href="socials.vk_url || '#'" target="_blank" rel="noopener" class="footer__social" aria-label="VK">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5.01 13.89h-1.42c-.54 0-.7-.43-1.66-1.41-.84-.8-1.2-.9-1.41-.9-.29 0-.37.08-.37.48v1.29c0 .34-.11.55-1 .55-1.48 0-3.12-.9-4.27-2.57-1.74-2.45-2.22-4.29-2.22-4.66 0-.2.08-.39.48-.39h1.42c.36 0 .49.16.63.54.69 2.01 1.85 3.77 2.33 3.77.18 0 .26-.08.26-.53V9.71c-.06-.93-.54-1.01-.54-1.34 0-.16.14-.33.36-.33h2.24c.3 0 .41.16.41.52v2.82c0 .29.13.39.22.39.18 0 .33-.1.66-.43 1.02-1.14 1.74-2.89 1.74-2.89.1-.2.26-.39.62-.39h1.42c.43 0 .52.22.43.52-.18.82-1.88 3.21-1.88 3.21-.15.24-.2.35 0 .62.15.2.63.62.96 1 .69.72 1.22 1.33 1.36 1.75.15.42-.08.64-.47.64z"/>
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a v-if="socials.instagram_enabled" :href="socials.instagram_url || '#'" target="_blank" rel="noopener" class="footer__social" aria-label="Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 011.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 01-1.153 1.772 4.915 4.915 0 01-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 01-1.772-1.153 4.904 4.904 0 01-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 011.153-1.772A4.897 4.897 0 015.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 5a5 5 0 100 10 5 5 0 000-10zm0 8.25a3.25 3.25 0 110-6.5 3.25 3.25 0 010 6.5zm5.884-8.625a1.125 1.125 0 11-2.25 0 1.125 1.125 0 012.25 0z"/>
                            </svg>
                        </a>
                        <!-- WhatsApp -->
                        <a v-if="socials.whatsapp_enabled" :href="socials.whatsapp_url || '#'" target="_blank" rel="noopener" class="footer__social" aria-label="WhatsApp">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.07L2 22l5.07-1.33A9.96 9.96 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm5.37 14.12c-.22.63-1.32 1.2-1.82 1.28-.5.07-1.13.1-1.82-.11-.42-.13-.96-.31-1.65-.6-2.88-1.24-4.76-4.15-4.9-4.34-.14-.19-1.13-1.5-1.13-2.87 0-1.36.72-2.04.97-2.31.26-.28.56-.34.75-.34.19 0 .37 0 .54.01.17.01.4-.07.63.48.22.55.78 1.9.85 2.04.07.14.11.29.02.47-.09.17-.14.28-.28.43-.14.15-.29.34-.42.45-.14.12-.28.25-.12.49.16.24.72 1.19 1.55 1.93 1.07.95 1.96 1.24 2.24 1.38.28.14.44.12.6-.07.16-.19.7-.81.89-1.09.19-.28.37-.23.63-.14.26.09 1.63.77 1.9.91.28.14.47.21.54.33.07.11.07.65-.15 1.28z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer__bottom">
                <span class="footer__copy">© {{ new Date().getFullYear() }} Ведьмина метла. Все права защищены.</span>
            </div>
        </div>
    </footer>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-bg-dark: #1a1a1a;
$color-white: #ffffff;

.footer {
    background: $color-bg-dark;
    color: rgba($color-white, 0.7);

    &__inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: clamp(32px, 5vw, 60px) clamp(16px, 4vw, 40px) 24px;
    }

    &__top {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
        gap: clamp(24px, 4vw, 60px);
        padding-bottom: 32px;
        border-bottom: 1px solid rgba($color-white, 0.08);

        @media (max-width: 768px) {
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        @media (max-width: 480px) {
            grid-template-columns: 1fr;
        }
    }

    &__brand {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    &__logo-icon {
        font-size: 28px;
        margin-bottom: 4px;
    }

    &__logo-text {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: $color-white;
    }

    &__tagline {
        font-size: 13px;
        color: rgba($color-white, 0.4);
        margin-top: 4px;
    }

    &__nav-title {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: $color-white;
        margin-bottom: 16px;
    }

    &__nav, &__contacts, &__socials {
        display: flex;
        flex-direction: column;
    }

    &__link {
        font-size: 13px;
        color: rgba($color-white, 0.5);
        margin-bottom: 10px;
        transition: color 0.3s ease;

        &:hover {
            color: $color-accent;
        }

        &.--address {
            cursor: default;
            &:hover { color: rgba($color-white, 0.5); }
        }
    }

    &__social-links {
        display: flex;
        gap: 12px;
    }

    &__social {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid rgba($color-white, 0.15);
        color: rgba($color-white, 0.5);
        transition: all 0.3s ease;

        &:hover {
            color: $color-accent;
            border-color: $color-accent;
        }
    }

    &__bottom {
        padding-top: 20px;
        text-align: center;
    }

    &__copy {
        font-size: 12px;
        color: rgba($color-white, 0.3);
    }
}
</style>
