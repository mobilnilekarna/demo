/**
 * Utility functions for order-related labels and formatting
 */

/**
 * Získání labelu pro stav objednávky
 * @param {string} status
 * @returns {string}
 */
export function getStatusLabel(status) {
    const labels = {
        pending: "Čeká na zpracování",
        processing: "Zpracovává se",
        completed: "Dokončeno",
        cancelled: "Zrušeno",
    };
    return labels[status] || status;
}

/**
 * Získání CSS třídy pro stav objednávky
 * @param {string} status
 * @returns {string}
 */
export function getStatusClass(status) {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800",
        processing: "bg-blue-100 text-blue-800",
        completed: "bg-primary-100 text-primary-800",
        cancelled: "bg-secondary-100 text-secondary-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
}

/**
 * Získání labelu pro způsob dopravy
 * @param {string} value
 * @returns {string}
 */
export function getShippingLabel(value) {
    const options = {
        standard: "Standardní doprava (99 Kč)",
        express: "Expresní doprava (199 Kč)",
        pickup: "Osobní odběr (Zdarma)",
        courier: "Kurýr (149 Kč)",
    };
    return options[value] || value;
}

/**
 * Získání labelu pro způsob platby
 * @param {string} value
 * @returns {string}
 */
export function getPaymentLabel(value) {
    const options = {
        card: "Kartou online",
        transfer: "Bankovním převodem",
        cod: "Dobírka",
        cash: "Hotově při převzetí",
    };
    return options[value] || value;
}

/**
 * Získání labelu pro zemi
 * @param {string} value
 * @returns {string}
 */
export function getCountryLabel(value) {
    const options = {
        CZ: "Česká republika",
        SK: "Slovensko",
    };
    return options[value] || value;
}

