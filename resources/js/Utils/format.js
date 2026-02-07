/**
 * Utility functions for formatting data
 */

/**
 * Format date to Czech locale
 * @param {Date|string} date
 * @returns {string}
 */
export function formatDate(date) {
    const d = new Date(date)
    return d.toLocaleDateString('cs-CZ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

/**
 * Format currency to Czech format
 * @param {number} amount
 * @param {string} currency
 * @returns {string}
 */
export function formatCurrency(amount, currency = 'CZK') {
    return new Intl.NumberFormat('cs-CZ', {
        style: 'currency',
        currency: currency
    }).format(amount)
}

/**
 * Format number with Czech locale
 * @param {number} number
 * @returns {string}
 */
export function formatNumber(number) {
    return new Intl.NumberFormat('cs-CZ').format(number)
}

/**
 * Capitalize first letter of string
 * @param {string} str
 * @returns {string}
 */
export function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1)
}

/**
 * Generate slug from string
 * @param {string} str
 * @returns {string}
 */
export function slugify(str) {
    return str
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '')
}
