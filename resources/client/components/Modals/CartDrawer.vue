<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useCart } from '@/stores/cart';

const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(['close']);
const { items, totalCount, totalPrice, incrementItem, decrementItem, removeItem, clearCart } = useCart();

// Форма заказа
const showOrderForm = ref(false);
const orderSuccess = ref(false);
const isSubmitting = ref(false);
const form = ref({
    customer_name: '',
    customer_phone: '',
    address: '',
    comment: '',
});

function openOrderForm() {
    showOrderForm.value = true;
}

function submitOrder() {
    if (!form.value.customer_name || !form.value.customer_phone || !form.value.address) return;

    isSubmitting.value = true;

    router.post('/order', {
        ...form.value,
        items: items.map(item => ({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
        })),
        total: totalPrice.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            orderSuccess.value = true;
            clearCart();
            form.value = { customer_name: '', customer_phone: '', address: '', comment: '' };

            setTimeout(() => {
                orderSuccess.value = false;
                showOrderForm.value = false;
                emit('close');
            }, 3000);
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
}

// Закрываем форму при закрытии дровера
watch(() => props.isOpen, (val) => {
    if (!val) {
        showOrderForm.value = false;
        orderSuccess.value = false;
    }
});

// Блокируем скролл при открытии
watch(() => props.isOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
});

// Цвет-заглушка
function getColor(id) {
    const colors = ['#e8ddd5', '#d5dde8', '#dde8d5', '#e8d5dd', '#d5e8dd', '#e0d8e8'];
    return colors[id % colors.length];
}
</script>

<template>
    <Transition name="cart-drawer">
        <div v-if="isOpen" class="cart-drawer">
            <div class="cart-drawer__overlay" @click="emit('close')" />
            <div class="cart-drawer__panel">
                <!-- Шапка -->
                <div class="cart-drawer__header">
                    <h3 class="cart-drawer__title">
                        Корзина
                        <span v-if="totalCount > 0" class="cart-drawer__count">({{ totalCount }})</span>
                    </h3>
                    <button class="cart-drawer__close" @click="emit('close')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>

                <!-- Успех -->
                <div v-if="orderSuccess" class="cart-drawer__success">
                    <span class="cart-drawer__success-icon">✓</span>
                    <p>Заказ успешно оформлен!</p>
                    <p class="cart-drawer__success-sub">Мы свяжемся с вами в ближайшее время</p>
                </div>

                <!-- Пустая корзина -->
                <div v-else-if="items.length === 0" class="cart-drawer__empty">
                    <span class="cart-drawer__empty-icon">🛒</span>
                    <p>Корзина пуста</p>
                    <p class="cart-drawer__empty-sub">Добавьте товары из каталога</p>
                </div>

                <!-- Содержимое -->
                <template v-else>
                    <!-- Форма заказа -->
                    <div v-if="showOrderForm" class="cart-drawer__form-wrap">
                        <form class="cart-drawer__form" @submit.prevent="submitOrder">
                            <div class="cart-drawer__field">
                                <label>Ваше имя *</label>
                                <input v-model="form.customer_name" type="text" required placeholder="Имя" />
                            </div>
                            <div class="cart-drawer__field">
                                <label>Телефон *</label>
                                <input v-model="form.customer_phone" type="tel" required placeholder="+7 (999) 123-45-67" />
                            </div>
                            <div class="cart-drawer__field">
                                <label>Адрес доставки *</label>
                                <textarea v-model="form.address" rows="2" required placeholder="Город, улица, дом, квартира" />
                            </div>
                            <div class="cart-drawer__field">
                                <label>Комментарий</label>
                                <textarea v-model="form.comment" rows="2" maxlength="500" placeholder="Пожелания к заказу..." />
                            </div>

                            <div class="cart-drawer__form-total">
                                <span>Итого:</span>
                                <strong>{{ totalPrice.toLocaleString('ru-RU') }} ₽</strong>
                            </div>

                            <button type="submit" class="btn btn--primary btn--full" :disabled="isSubmitting">
                                {{ isSubmitting ? 'Отправка...' : 'Подтвердить заказ' }}
                            </button>
                            <button type="button" class="btn btn--secondary btn--full" @click="showOrderForm = false" style="margin-top: 8px">
                                Назад
                            </button>
                        </form>
                    </div>

                    <!-- Список товаров -->
                    <div v-else class="cart-drawer__items">
                        <div v-for="item in items" :key="item.id" class="cart-item">
                            <div class="cart-item__image">
                                <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" />
                                <div v-else class="cart-item__placeholder" :style="{ backgroundColor: getColor(item.id) }">
                                    <span>🌸</span>
                                </div>
                            </div>
                            <div class="cart-item__info">
                                <span class="cart-item__name">{{ item.name }}</span>
                                <span class="cart-item__price">{{ (item.price * item.quantity).toLocaleString('ru-RU') }} ₽</span>
                            </div>
                            <div class="cart-item__controls">
                                <div class="cart-item__counter">
                                    <button @click="decrementItem(item.id)">−</button>
                                    <span>{{ item.quantity }}</span>
                                    <button @click="incrementItem(item.id)">+</button>
                                </div>
                                <button class="cart-item__remove" @click="removeItem(item.id)" aria-label="Удалить">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Итого и кнопка -->
                    <div v-if="!showOrderForm" class="cart-drawer__footer">
                        <div class="cart-drawer__total">
                            <span>Итого:</span>
                            <strong>{{ totalPrice.toLocaleString('ru-RU') }} ₽</strong>
                        </div>
                        <button class="btn btn--primary btn--full" @click="openOrderForm">
                            Оформить заказ
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </Transition>
</template>

<style lang="scss">
$color-primary: #2d4a2d;
$color-accent: #c4a0a0;
$color-white: #ffffff;
$color-bg: #fafafa;

.cart-drawer {
    position: fixed;
    inset: 0;
    z-index: 200;

    &__overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
    }

    &__panel {
        position: absolute;
        top: 0;
        right: 0;
        width: 420px;
        max-width: 100%;
        height: 100%;
        background: $color-white;
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 30px rgba(0, 0, 0, 0.12);
    }

    &__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #eee;
    }

    &__title {
        font-size: 18px;
        font-weight: 700;
        color: #2c2c2c;
    }

    &__count {
        font-weight: 400;
        color: #999;
    }

    &__close {
        color: #999;
        transition: color 0.3s ease;
        &:hover { color: #333; }
    }

    &__empty, &__success {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #999;

        p { font-size: 16px; font-weight: 500; }
        &-sub { font-size: 13px; color: #bbb; }
        &-icon { font-size: 48px; margin-bottom: 8px; }
    }

    &__success-icon {
        width: 56px;
        height: 56px;
        background: $color-primary;
        color: $color-white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 8px;
    }

    &__items {
        flex: 1;
        overflow-y: auto;
        padding: 16px 24px;
    }

    &__footer {
        padding: 20px 24px;
        border-top: 1px solid #eee;
    }

    &__total, &__form-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        font-size: 16px;

        strong {
            font-size: 20px;
            color: $color-primary;
        }
    }

    // Форма
    &__form-wrap {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
    }

    &__form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    &__field {
        display: flex;
        flex-direction: column;
        gap: 4px;

        label {
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #777;
        }

        input, textarea {
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;

            &:focus {
                border-color: $color-primary;
            }
        }
    }
}

// Элемент корзины
.cart-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;

    &__image {
        width: 56px;
        height: 56px;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    &__placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    &__info {
        flex: 1;
        min-width: 0;
    }

    &__name {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #2c2c2c;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__price {
        font-size: 14px;
        font-weight: 600;
        color: $color-primary;
    }

    &__controls {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    &__counter {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;

        button {
            width: 28px;
            height: 28px;
            font-size: 14px;
            font-weight: 600;
            color: $color-primary;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;

            &:hover {
                background: #f5f5f5;
            }
        }

        span {
            width: 24px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
        }
    }

    &__remove {
        color: #ccc;
        transition: color 0.3s ease;
        &:hover { color: #e74c3c; }
    }
}

// Анимация
.cart-drawer-enter-active,
.cart-drawer-leave-active {
    transition: opacity 0.3s ease;

    .cart-drawer__panel {
        transition: transform 0.3s ease;
    }
}

.cart-drawer-enter-from,
.cart-drawer-leave-to {
    opacity: 0;

    .cart-drawer__panel {
        transform: translateX(100%);
    }
}
</style>
