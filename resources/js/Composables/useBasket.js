import { ref, computed, watch } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import { useAnalytics } from "@/Composables/useAnalytics.js"

const isModalOpen = ref(false)
const isBasketOpen = ref(false)
const basketItems = ref([])
const basketItem = ref(null);
const { trackAddToBasket, trackRemoveFromBasket } = useAnalytics()
const page = usePage();

let initialized = false

export function useBasket() {

    // Inicializace z props - POUZE JEDNOU při prvním zavolání
    if (!initialized) {
        basketItems.value = page.props?.basket || [];
        initialized = true
    }

    // === Computed ===
    const totalItems = computed(() =>
        basketItems.value.reduce((total, item) => total + item.quantity, 0)
    )
    const totalPrice = computed(() =>
        basketItems.value.reduce((total, item) => total + item.price * item.quantity, 0)
    )

    const isEmpty = computed(() => basketItems.value.length === 0)

    const addToBasket = async (product, quantity = 1) => {
        try {
            console.log('🛒 Adding to basket, product.id:', product.id)
            const { data } = await axios.post(
                route('basket.store', {}, false),
                { product: product, quantity }
            )
            console.log('📦 Backend response data.basket:', data.basket)
            basketItem.value = product
            basketItems.value = data.basket
            console.log('✅ basketItems.value updated to:', basketItems.value)
            console.log('🔍 Checking if product is in basket:', basketItems.value.some(i => i.model_id === product.id))
            trackAddToBasket(product, quantity)
        } catch (e) {
            console.error('❌ Error adding to basket', e)
            throw e
        }
    }

    const updateQuantity = async (id, newQuantity) => {
        try {
            const { data } = await axios.put(
                route('basket.update', { id: id }),
                { quantity: newQuantity }
            )

            basketItems.value = data.basket
        } catch (e) {
            alert(e.message);
            console.error('❌ Error updating basket', e)
        }
    }

    const removeFromBasket = async (productId) => {
        try {
            const { data } = await axios.delete(
                route('basket.destroy', { productId: productId })
            )
            basketItems.value = data.basket
            trackRemoveFromBasket({ id: productId }, 1)
        } catch (e) {
            console.error('❌ Error removing item', e)
        }
    }

    const clearBasket = async () => {
        try {
            const { data } = await axios.delete(
                route('basket.clear')
            )
            basketItems.value = data.basket
        } catch (e) {
            console.error('❌ Error clearing basket', e)
        }
    }

    const getItemId = (id) => {
        const item = basketItems.value.find(i => i.model_id === id)
        return item ? item.id : null
    }

    // === Utils ===
    const getItemQuantity = (id) => {
        const item = basketItems.value.find(i => i.model_id === id)
        return item ? item.quantity : 0
    }

    // Vrací reaktivní computed property pro kontrolu, jestli je produkt v košíku
    const isInBasket = (id) => {
        return computed(() => basketItems.value.some(i => i.model_id === id))
    }

    const toggleBasket = () => { isBasketOpen.value = !isBasketOpen.value }
    const openBasket = () => { isBasketOpen.value = true }
    const closeBasket = () => { isBasketOpen.value = false }
    const openModal = () => {
        console.log("Opening modal");
        console.log(basketItems.value);
        isModalOpen.value = true
    }
    const closeModal = () => {
        isModalOpen.value = false;
    }

    return {
        basketItem, basketItems, isBasketOpen, isModalOpen,
        totalItems, totalPrice, isEmpty,
        addToBasket, updateQuantity, removeFromBasket, clearBasket,
        getItemId, getItemQuantity, isInBasket,
        toggleBasket, openBasket, closeBasket, openModal, closeModal
    }
}

