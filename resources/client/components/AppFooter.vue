<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import logoWide from '@/assets/images/logo-wide.png';

const page = usePage();
const contacts = computed(() => page.props.footer?.contacts || {});
const socials = computed(() => page.props.footer?.socials || {});
</script>

<template>
    <footer class="footer">
        <div class="footer__inner">
            <div class="footer__top">
                <div class="footer__left">
                    <img :src="logoWide" alt="Ведьмина метла" class="footer__logo-img" />
                </div>

                <div class="footer__right">
                    <div class="footer__cols">
                        <div>
                            <h6 class="footer__col-title">Навигация</h6>
                            <p><Link href="/">Главная</Link></p>
                            <p><Link href="/catalog">Каталог</Link></p>
                            <p><Link href="/gallery">Галерея</Link></p>
                            <p><Link href="/#contacts">Контакты</Link></p>
                        </div>

                        <div>
                            <h6 class="footer__col-title">Контакты</h6>
                            <p v-if="contacts.phone">
                                <a :href="`tel:${contacts.phone.replace(/[^+\\d]/g, '')}`">{{ contacts.phone }}</a>
                            </p>
                            <p v-else>+7 900 000-00-00</p>
                            <p v-if="contacts.email">
                                <a :href="`mailto:${contacts.email}`">{{ contacts.email }}</a>
                            </p>
                            <p v-else>hello@vedmina-metla.ru</p>
                        </div>

                        <div>
                            <h6 class="footer__col-title">Мы в соцсетях</h6>
                            <p v-if="socials.instagram_enabled">
                                <a :href="socials.instagram_url || '#'" target="_blank" rel="noopener">Instagram</a>
                                <span class="footer__meta-note">* принадлежит Meta, запрещённой в РФ</span>
                            </p>
                            <p v-if="socials.telegram_enabled">
                                <a :href="socials.telegram_url || '#'" target="_blank" rel="noopener">Telegram</a>
                                <span class="footer__meta-note">* заблокирован в РФ</span>
                            </p>
                            <p v-if="socials.vk_enabled">
                                <a :href="socials.vk_url || '#'" target="_blank" rel="noopener">VK</a>
                            </p>
                            <p v-if="socials.whatsapp_enabled">
                                <a :href="socials.whatsapp_url || '#'" target="_blank" rel="noopener">WhatsApp</a>
                                <span class="footer__meta-note">* принадлежит Meta, запрещённой в РФ</span>
                            </p>
                            <p v-if="socials.avito_enabled">
                                <a :href="socials.avito_url || '#'" target="_blank" rel="noopener">Авито</a>
                            </p>
                            <p v-if="socials.max_enabled">
                                <a :href="socials.max_url || '#'" target="_blank" rel="noopener">Макс</a>
                            </p>
                            <template v-if="!socials.telegram_enabled && !socials.vk_enabled && !socials.instagram_enabled && !socials.whatsapp_enabled && !socials.avito_enabled && !socials.max_enabled">
                                <p>Instagram*</p>
                                <p>Telegram</p>
                                <p>VK</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </footer>
</template>

<style lang="scss">
$night: #150d10;
$gold: #c9a15a;

.footer {
    background: $night;
    padding: 40px;

    @media (max-width: 768px) {
        padding: 32px 16px;
    }

    &__inner {
        width: 100%;
    }

    &__top {
        display: flex;
        align-items: center;
        justify-content: space-between;

        @media (max-width: 768px) {
            flex-direction: column;
            align-items: flex-start;
            gap: 32px;
        }
    }

    &__left {
        flex-shrink: 0;
    }

    &__right {
        flex-shrink: 0;
    }

    &__logo-img {
        height: 100px;
        width: auto;

        @media (max-width: 768px) {
            height: 70px;
        }
    }

    &__cols {
        display: flex;
        gap: 80px;
        flex-wrap: wrap;
        font-size: 15px;
        color: #b7a89f;

        @media (max-width: 768px) {
            gap: 40px;
        }

        p {
            margin: 6px 0;
        }

        a {
            color: #b7a89f;
            transition: color 0.3s ease;

            &:hover {
                color: $gold;
            }
        }
    }

    &__col-title {
        color: $gold;
        font-family: 'Work Sans', sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 10px;
    }

    &__meta-note {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.3);
        margin-left: 4px;
    }

}
</style>
