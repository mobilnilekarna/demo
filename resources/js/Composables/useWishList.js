import { ref, computed, watch } from 'vue'

const STORAGE_KEY = 'wishlist'
const wishlistItems = ref([])

// Načtení z localStorage při inicializaci
const loadWishlistFromStorage = () => {
    try {
        const stored = localStorage.getItem(STORAGE_KEY)
        if (stored) {
            wishlistItems.value = JSON.parse(stored)
        }
    } catch (error) {
        console.error('❌ Error loading wishlist from localStorage:', error)
        wishlistItems.value = []
    }
}

// Uložení do localStorage
const saveWishlistToStorage = () => {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(wishlistItems.value))
    } catch (error) {
        console.error('❌ Error saving wishlist to localStorage:', error)
    }
}

// Inicializace při prvním načtení
if (wishlistItems.value.length === 0) {
    loadWishlistFromStorage()
}

// Watch pro automatické ukládání při změně
watch(wishlistItems, () => {
    saveWishlistToStorage()
}, { deep: true })

export function useWishList() {
    // === Computed ===
    const totalItems = computed(() => wishlistItems.value.length)

    const isEmpty = computed(() => wishlistItems.value.length === 0)

    // === Methods ===
    const addToWishlist = (product) => {
        try {
            // Kontrola, jestli už produkt není v wishlistu
            const exists = wishlistItems.value.some(item => item.id === product.id)

            if (exists) {
                console.log('⚠️ Product already in wishlist')
                return false
            }

            // Přidání produktu
            wishlistItems.value.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image || product.images?.[0] || '',
                slug: product.slug || null,
                ...product
            })

            console.log('✅ Product added to wishlist')
            return true
        } catch (error) {
            console.error('❌ Error adding to wishlist:', error)
            return false
        }
    }

    const removeFromWishlist = (productId) => {
        try {
            const index = wishlistItems.value.findIndex(item => item.id === productId)

            if (index === -1) {
                console.log('⚠️ Product not found in wishlist')
                return false
            }

            wishlistItems.value.splice(index, 1)
            console.log('✅ Product removed from wishlist')
            return true
        } catch (error) {
            console.error('❌ Error removing from wishlist:', error)
            return false
        }
    }

    const toggleWishlist = (product) => {
        const exists = wishlistItems.value.some(item => item.id === product.id)

        if (exists) {
            return removeFromWishlist(product.id)
        } else {
            return addToWishlist(product)
        }
    }

    const clearWishlist = () => {
        try {
            wishlistItems.value = []
            console.log('✅ Wishlist cleared')
            return true
        } catch (error) {
            console.error('❌ Error clearing wishlist:', error)
            return false
        }
    }

    // Vrací reaktivní computed property pro kontrolu, jestli je produkt v wishlistu
    // Použití: const isInWishlistComputed = isInWishlist(productId)
    const isInWishlist = (productId) => {
        return computed(() => wishlistItems.value.some(item => item.id === productId))
    }

    const getWishlistItem = (productId) => {
        return wishlistItems.value.find(item => item.id === productId) || null
    }

    return {
        wishlistItems,
        totalItems,
        isEmpty,
        addToWishlist,
        removeFromWishlist,
        toggleWishlist,
        clearWishlist,
        isInWishlist,
        getWishlistItem
    }
}

