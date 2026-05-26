import { reactive, computed } from 'vue';

// Реактивное хранилище корзины с localStorage
const STORAGE_KEY = 'vedma_cart';

function loadCart() {
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];
    } catch {
        return [];
    }
}

function saveCart(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
}

const state = reactive({
    items: loadCart(),
});

// Общее количество товаров
const totalCount = computed(() =>
    state.items.reduce((sum, item) => sum + item.quantity, 0)
);

// Итоговая сумма
const totalPrice = computed(() =>
    state.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
);

// Добавить товар в корзину
function addItem(product) {
    const existing = state.items.find((item) => item.id === product.id);

    if (existing) {
        existing.quantity++;
    } else {
        state.items.push({
            id: product.id,
            name: product.name,
            price: Number(product.price),
            image: product.images?.find(img => img && !img.startsWith('/')) || null,
            quantity: 1,
        });
    }
    saveCart(state.items);
}

// Убрать одну единицу товара
function decrementItem(productId) {
    const index = state.items.findIndex((item) => item.id === productId);
    if (index === -1) return;

    if (state.items[index].quantity > 1) {
        state.items[index].quantity--;
    } else {
        state.items.splice(index, 1);
    }
    saveCart(state.items);
}

// Увеличить количество
function incrementItem(productId) {
    const item = state.items.find((item) => item.id === productId);
    if (item) {
        item.quantity++;
        saveCart(state.items);
    }
}

// Удалить товар полностью
function removeItem(productId) {
    const index = state.items.findIndex((item) => item.id === productId);
    if (index !== -1) {
        state.items.splice(index, 1);
        saveCart(state.items);
    }
}

// Получить количество конкретного товара в корзине
function getItemQuantity(productId) {
    const item = state.items.find((item) => item.id === productId);
    return item ? item.quantity : 0;
}

// Очистить корзину
function clearCart() {
    state.items.splice(0, state.items.length);
    saveCart(state.items);
}

export function useCart() {
    return {
        items: state.items,
        totalCount,
        totalPrice,
        addItem,
        decrementItem,
        incrementItem,
        removeItem,
        getItemQuantity,
        clearCart,
    };
}
