/**
 * Utility funkce pro práci s enumy a kontrolu datových typů
 */

import BasketType from '@/Enums/BasketType';
import BasketItemType from '@/Enums/BasketItemType';
import Entity from '@/Enums/Entity';

/**
 * Type guard - kontrola, zda je hodnota validní BasketType
 */
export const isBasketType = (value) => {
    return Object.values(BasketType).includes(value);
};

/**
 * Type guard - kontrola, zda je hodnota validní BasketItemType
 */
export const isBasketItemType = (value) => {
    return Object.values(BasketItemType).includes(value);
};

/**
 * Type guard - kontrola, zda je hodnota validní Entity
 */
export const isEntityType = (value) => {
    return Object.values(Entity).includes(value);
};

/**
 * Bezpečné získání BasketType s fallback hodnotou
 */
export const getBasketType = (value, defaultValue = BasketType.OPEN) => {
    return isBasketType(value) ? value : defaultValue;
};

/**
 * Bezpečné získání BasketItemType s fallback hodnotou
 */
export const getBasketItemType = (value, defaultValue = BasketItemType.PRODUCT) => {
    return isBasketItemType(value) ? value : defaultValue;
};

/**
 * Bezpečné získání Entity s fallback hodnotou
 */
export const getEntityType = (value, defaultValue = Entity.PRODUCT) => {
    return isEntityType(value) ? value : defaultValue;
};

/**
 * Validace objektu s enum hodnotami
 * Vrací pole chyb nebo prázdné pole, pokud je vše v pořádku
 */
export const validateEnums = (data) => {
    const errors = [];

    if (data.basketType && !isBasketType(data.basketType)) {
        errors.push(`Neplatný BasketType: ${data.basketType}`);
    }

    if (data.itemType && !isBasketItemType(data.itemType)) {
        errors.push(`Neplatný BasketItemType: ${data.itemType}`);
    }

    if (data.entityType && !isEntityType(data.entityType)) {
        errors.push(`Neplatný Entity: ${data.entityType}`);
    }

    return errors;
};

/**
 * Kontrola, zda je košík v editovatelném stavu
 */
export const isBasketEditable = (basketType) => {
    return basketType === BasketType.OPEN;
};

/**
 * Kontrola, zda je košík dokončený
 */
export const isBasketCompleted = (basketType) => {
    return basketType === BasketType.CHECKED_OUT;
};

/**
 * Kontrola, zda je košík opuštěný
 */
export const isBasketAbandoned = (basketType) => {
    return basketType === BasketType.ABANDONED;
};

/**
 * Získání labelu pro BasketType
 */
export const getBasketTypeLabel = (basketType) => {
    const labels = {
        [BasketType.OPEN]: 'Otevřený',
        [BasketType.CHECKED_OUT]: 'Dokončený',
        [BasketType.ABANDONED]: 'Opuštěný'
    };

    return labels[basketType] || 'Neznámý';
};

/**
 * Získání labelu pro BasketItemType
 */
export const getBasketItemTypeLabel = (itemType) => {
    const labels = {
        [BasketItemType.PRODUCT]: 'Produkt'
    };

    return labels[itemType] || 'Neznámý typ';
};

/**
 * Získání labelu pro Entity
 */
export const getEntityLabel = (entityType) => {
    const labels = {
        [Entity.PRODUCT]: 'Produkt',
        [Entity.ARTICLE]: 'Článek',
        [Entity.USER]: 'Uživatel',
        [Entity.PAGE]: 'Stránka',
        [Entity.BANNER]: 'Banner',
        [Entity.BRAND]: 'Značka',
        [Entity.MESSAGE]: 'Zpráva',
        [Entity.REGISTER]: 'Registrace',
        [Entity.CUSTOMER]: 'Zákazník',
        [Entity.TRANSPORT]: 'Doprava',
        [Entity.PAYMENT]: 'Platba',
        [Entity.ATTRIBUTE]: 'Atribut'
    };

    return labels[entityType] || 'Neznámá entita';
};

/**
 * Získání CSS třídy pro BasketType
 */
export const getBasketTypeClass = (basketType) => {
    const classes = {
        [BasketType.OPEN]: 'bg-green-100 text-green-800',
        [BasketType.CHECKED_OUT]: 'bg-blue-100 text-blue-800',
        [BasketType.ABANDONED]: 'bg-gray-100 text-gray-800'
    };

    return classes[basketType] || 'bg-gray-100 text-gray-800';
};

/**
 * Získání ikony pro Entity
 */
export const getEntityIcon = (entityType) => {
    const icons = {
        [Entity.PRODUCT]: '📦',
        [Entity.ARTICLE]: '📄',
        [Entity.USER]: '👤',
        [Entity.PAGE]: '📃',
        [Entity.BANNER]: '🖼️',
        [Entity.BRAND]: '🏷️',
        [Entity.MESSAGE]: '💬',
        [Entity.REGISTER]: '📝',
        [Entity.CUSTOMER]: '👥',
        [Entity.TRANSPORT]: '🚚',
        [Entity.PAYMENT]: '💳',
        [Entity.ATTRIBUTE]: '🏷️'
    };

    return icons[entityType] || '❓';
};

