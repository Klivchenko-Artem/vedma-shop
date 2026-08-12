<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useCart } from '@/stores/cart';

const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(['close']);
const { items, totalCount, totalPrice, incrementItem, decrementItem, removeItem, clearCart } = useCart();

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

watch(() => props.isOpen, (val) => {
    if (!val) {
        showOrderForm.value = false;
        orderSuccess.value = false;
    }
});

watch(() => props.isOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
});

function getGradient(id) {
    const gradients = [
        'linear-gradient(135deg, #7a1220, #6b4c82)',
        'linear-gradient(135deg, #4f0a12, #c9a15a)',
        'linear-gradient(135deg, #6b4c82, #7a1220)',
        'linear-gradient(135deg, #c9a15a, #4f0a12)',
    ];
    return gradients[id % gradients.length];
}
</script>

<template>
    <Transition name="cart-drawer">
        <div v-if="isOpen" class="cart-drawer">
            <div class="cart-drawer__overlay" @click="emit('close')" />
            <div class="cart-drawer__panel">
                <!-- Шапка -->
                <div class="cart-drawer__header">
                    <h4 class="cart-drawer__title">Корзина</h4>
                    <button class="cart-drawer__close" @click="emit('close')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>

                <!-- Успех -->
                <div v-if="orderSuccess" class="cart-drawer__success">
                    <div class="cart-drawer__success-icon">&#x2713;</div>
                    <h4>Заказ успешно оформлен</h4>
                    <p class="cart-drawer__success-sub">Мы свяжемся с вами в ближайшее время</p>
                </div>

                <!-- Пустая корзина -->
                <div v-else-if="items.length === 0" class="cart-drawer__empty">
                    <p>Корзина пуста</p>
                </div>

                <!-- Содержимое -->
                <template v-else>
                    <!-- Форма заказа -->
                    <div v-if="showOrderForm" class="cart-drawer__form-wrap">
                        <form class="cart-drawer__form" @submit.prevent="submitOrder">
                            <h4 class="cart-drawer__form-title">Оформление заказа</h4>

                            <div class="cart-drawer__field">
                                <label>Ваше имя *</label>
                                <input v-model="form.customer_name" type="text" required placeholder="Анна" />
                            </div>
                            <div class="cart-drawer__field">
                                <label>Телефон заказчика *</label>
                                <input v-model="form.customer_phone" type="tel" required placeholder="+7 900 000-00-00" />
                            </div>
                            <div class="cart-drawer__field">
                                <label>Адрес доставки *</label>
                                <input v-model="form.address" type="text" required placeholder="ул. Тёмная, д. 13" />
                            </div>
                            <div class="cart-drawer__field">
                                <label>Комментарий к заказу</label>
                                <textarea v-model="form.comment" rows="1" placeholder="Добавить открытку, особые пожелания..." @input="e => { e.target.style.height = 'auto'; e.target.style.height = e.target.scrollHeight + 'px' }"></textarea>
                            </div>
                            <div class="cart-drawer__form-total">
                                <span>Итого к оплате</span>
                                <strong>{{ totalPrice.toLocaleString('ru-RU') }} &#8381;</strong>
                            </div>

                            <button type="submit" class="cart-drawer__checkout-btn" :disabled="isSubmitting">
                                {{ isSubmitting ? 'Отправка...' : 'Подтвердить заказ' }}
                            </button>
                            <button type="button" class="cart-drawer__back-btn" @click="showOrderForm = false">
                                Назад
                            </button>
                        </form>
                    </div>

                    <!-- Список товаров -->
                    <div v-else class="cart-drawer__items">
                        <div v-for="item in items" :key="item.id" class="cart-line">
                            <div class="cart-line__thumb">
                                <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" />
                                <div v-else class="cart-line__thumb-bg" :style="{ background: getGradient(item.id) }"></div>
                            </div>
                            <div class="cart-line__info">
                                <div class="cart-line__name">{{ item.name }}</div>
                                <span class="cart-line__price">{{ (item.price * item.quantity).toLocaleString('ru-RU') }} &#8381;</span>
                            </div>
                            <div class="cart-line__qty">
                                <button @click="decrementItem(item.id)">-</button>
                                <span>{{ item.quantity }}</span>
                                <button @click="incrementItem(item.id)">+</button>
                            </div>
                            <span class="cart-line__remove" @click="removeItem(item.id)">&#x2715;</span>
                        </div>
                    </div>

                    <!-- Итого -->
                    <div v-if="!showOrderForm" class="cart-drawer__footer">
                        <div class="cart-drawer__total">
                            <span>Итого</span>
                            <strong>{{ totalPrice.toLocaleString('ru-RU') }} &#8381;</strong>
                        </div>
                        <button class="cart-drawer__checkout-btn" @click="openOrderForm">
                            Оформить заказ
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </Transition>
</template>

<style lang="scss">
$parchment: #f4ead9;
$parchment-dim: #e9dcc4;
$ink: #1c1114;
$night: #150d10;
$wine: #7a1220;
$gold: #c9a15a;

.cart-drawer {
    position: fixed;
    inset: 0;
    z-index: 200;

    &__overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    &__panel {
        position: absolute;
        top: 0;
        right: 0;
        width: 380px;
        max-width: 100%;
        height: 100%;
        background: $parchment;
        border-radius: 14px 0 0 14px;
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 30px rgba(0, 0, 0, 0.2);
        color: $ink;
    }

    &__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #e4d6bd;
    }

    &__title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        color: $ink;
    }

    &__close {
        color: #8a7c70;
        transition: color 0.3s ease;
        &:hover { color: $ink; }
    }

    &__empty, &__success {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #6b5f56;
        text-align: center;
        padding: 24px;
    }

    &__success-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: $wine;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin-bottom: 16px;
    }

    &__success-sub {
        font-size: 13px;
        color: #6b5f56;
    }

    &__items {
        flex: 1;
        overflow-y: auto;
        padding: 12px 24px;
    }

    &__footer {
        padding: 20px 24px;
        border-top: 1px solid #e4d6bd;
    }

    &__total, &__form-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        font-weight: 600;

        strong {
            font-size: 18px;
            color: $wine;
        }
    }

    &__checkout-btn {
        width: 100%;
        background: $wine;
        color: #fff;
        border: none;
        padding: 14px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s ease;

        &:hover {
            background: lighten($wine, 5%);
        }

        &:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    }

    &__back-btn {
        width: 100%;
        margin-top: 10px;
        background: transparent;
        border: 1px solid $gold;
        color: $wine;
        padding: 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;

        &:hover {
            background: $gold;
            color: $night;
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
        gap: 12px;
    }

    &__form-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        margin-bottom: 4px;
    }

    &__field {
        display: flex;
        flex-direction: column;
        gap: 4px;

        label {
            font-size: 12px;
            color: #6b5f56;
        }

        input, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d8c7a8;
            border-radius: 8px;
            font-family: 'Work Sans', sans-serif;
            font-size: 14px;
            background: #fff;
            transition: border-color 0.3s ease;

            &:focus {
                border-color: $wine;
            }
        }

        textarea {
            resize: none;
            overflow: hidden;
        }
    }

}

// Элемент корзины
.cart-line {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #e4d6bd;

    &__thumb {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        &-bg {
            width: 100%;
            height: 100%;
        }
    }

    &__info {
        flex: 1;
        min-width: 0;
    }

    &__name {
        font-size: 14px;
        font-weight: 500;
        color: $ink;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__price {
        font-size: 13px;
        font-weight: 600;
        color: $wine;
    }

    &__qty {
        display: flex;
        align-items: center;
        gap: 8px;

        button {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid #d8c7a8;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.2s ease;

            &:hover {
                border-color: $wine;
                color: $wine;
            }
        }

        span {
            font-size: 13px;
            font-weight: 600;
            min-width: 16px;
            text-align: center;
        }
    }

    &__remove {
        cursor: pointer;
        color: #8a7c70;
        font-size: 14px;
        transition: color 0.3s ease;

        &:hover {
            color: $wine;
        }
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
