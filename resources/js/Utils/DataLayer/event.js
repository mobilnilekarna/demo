// Funkce, která sestaví datový objekt pro událost 'addToBasket'
export const buildAddToBasketEvent = (product, quantity = 1) => ({
    event: 'addToBasket',
    ecommerce: {
        currency: 'CZK',
        items: [{
            item_id: product.id,
            item_name: product.name,
            price: product.price,
            quantity: quantity
        }]
    }
});

// Funkce, která sestaví datový objekt pro událost 'removeFromBasket'
export const buildRemoveFromBasketEvent = (product, quantity = 1) => ({
    event: 'removeFromBasket',
    ecommerce: {
        currency: 'CZK',
        items: [{
            item_id: product.id,
            item_name: product.name,
            price: product.price,
            quantity: quantity
        }]
    }
});

export const buildRegisterUserEvent = (method) => ({
    event: 'register',
    method : method
});

export const buildLoginUserEvent = (method) => ({
    event: 'login',
    method : method
});

export const buildLogoutUserEvent = (method) => ({
    event: 'logout',
    method : method
});

// Funkce, která sestaví datový objekt pro událost 'search'
export const buildSearchEvent = (query) => ({
    event: 'search',
    search_query: query
});

export const buildSubscribeEvent = (email) => ({
    event: 'subscribe',
    email: email
});
