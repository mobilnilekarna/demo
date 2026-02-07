import { ref, watch } from 'vue';

const STORAGE_KEY = 'checkout_data';

const defaultData = {
    // Typ doručení (pickup = výdejní místo, address = na adresu)
    deliveryType: null,
    // Doprava a platba - ukládáme jako objekty pro zachování všech informací
    transport: null,
    payment: null,
    // Výdejní místo (pro dopravy typu pickup)
    branch_id: null,
    branch_name: null,

    // Základní údaje
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    phonePrefix: '+420',

    // Firemní údaje
    isCompany: false,
    companyName: '',
    companyId: '',
    companyVatId: '',

    // Dodací údaje
    hasDeliveryAddress: false,
    deliveryFirstName: '',
    deliveryLastName: '',
    deliveryStreet: '',
    deliveryCity: '',
    deliveryZip: '',
    deliveryCountry: 'CZ',

    // Fakturační údaje
    street: '',
    city: '',
    zip: '',
    country: 'CZ',

    // Poznámka
    note: '',
};

// Načtení dat z localStorage
const loadFromStorage = () => {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            return { ...defaultData, ...JSON.parse(stored) };
        }
    } catch (error) {
        console.error('Error loading checkout data from localStorage:', error);
    }
    return { ...defaultData };
};

// Uložení dat do localStorage
const saveToStorage = (data) => {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    } catch (error) {
        console.error('Error saving checkout data to localStorage:', error);
    }
};

const clearStorage = () => {
    try {
        localStorage.removeItem(STORAGE_KEY);
    } catch (error) {
        console.error('Error clearing checkout data from localStorage:', error);
    }
};

// Singleton instance formData - sdílená mezi všemi voláními useCheckout()
let formDataInstance = null;

export function useCheckout() {
    // Pokud instance neexistuje, vytvoříme ji
    if (!formDataInstance) {
        formDataInstance = ref(loadFromStorage());

        // Sledování změn a automatické ukládání
        watch(
            formDataInstance,
            (newData) => {
                saveToStorage(newData);
            },
            { deep: true }
        );
    }

    const formData = formDataInstance;

    // Reset formuláře
    const resetForm = () => {
        formData.value = { ...defaultData };
        localStorage.removeItem(STORAGE_KEY);
    };

    // Vyplnění formuláře ukázkovými daty
    const fillSampleData = () => {
        formData.value = {
            transport: null, // Bude nastaveno podle dostupných dat
            payment: null, // Bude nastaveno podle dostupných dat
            firstName: 'Jan',
            lastName: 'Novák',
            email: 'jan.novak@example.com',
            phone: '123456789',
            phonePrefix: '+420',
            isCompany: false,
            companyName: '',
            companyId: '',
            companyVatId: '',
            hasDeliveryAddress: false,
            deliveryFirstName: '',
            deliveryLastName: '',
            deliveryStreet: '',
            deliveryCity: '',
            deliveryZip: '',
            deliveryCountry: 'CZ',
            street: 'Hlavní 123',
            city: 'Praha',
            zip: '12000',
            country: 'CZ',
            note: 'Prosím o rychlé doručení.',
        };
    };

    // Vyplnění formuláře údaji z přihlášeného uživatele
    const fillUserData = (user) => {
        if (!user) return;

        // Rozdělení jména na firstName a lastName
        const nameParts = (user.name || '').trim().split(' ');
        const firstName = nameParts[0] || '';
        const lastName = nameParts.slice(1).join(' ') || '';

        // Vyplnění základních údajů - přímá změna vlastností pro zachování reaktivity
        formData.value.firstName = firstName;
        formData.value.lastName = lastName;
        if (user.email) {
            formData.value.email = user.email;
        }
    };

    // Validace emailu
    const validateEmail = (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    };

    // Validace telefonního čísla (CZ/SK formát)
    const validatePhone = (phone) => {
        const re = /^(\+420|\+421)?[1-9][0-9]{8}$/;
        return re.test(phone.replace(/\s/g, ''));
    };

    // Validace IČO (8 číslic)
    const validateCompanyId = (id) => {
        const re = /^\d{8}$/;
        return re.test(id);
    };

    // Validace DIČ (CZ formát)
    const validateVatId = (vatId) => {
        const re = /^CZ\d{8,10}$/;
        return re.test(vatId);
    };

    // Validace PSČ (5 číslic)
    const validateZip = (zip) => {
        const re = /^\d{5}$/;
        return re.test(zip);
    };

    // Validace celého formuláře
    const validateForm = (checkoutConfig = {}) => {
        const errors = {};

        // Výchozí hodnota pro typ checkoutu
        const checkoutType = checkoutConfig.type ?? 'advanced';

        // Typ doručení
        if (!formData.value.deliveryType) {
            errors.deliveryType = 'Vyberte typ doručení';
        }
        // Doprava a platba - validace objektů
        if (!formData.value.transport || !formData.value.transport.id) {
            errors.transport = 'Vyberte způsob dopravy';
        }
        if (!formData.value.payment || !formData.value.payment.id) {
            errors.payment = 'Vyberte způsob platby';
        }

        // Základní údaje
        if (!formData.value.firstName.trim()) {
            errors.firstName = 'Jméno je povinné';
        }
        if (!formData.value.lastName.trim()) {
            errors.lastName = 'Příjmení je povinné';
        }
        if (!formData.value.email.trim()) {
            errors.email = 'Email je povinný';
        } else if (!validateEmail(formData.value.email)) {
            errors.email = 'Neplatný formát emailu';
        }
        if (!formData.value.phone.trim()) {
            errors.phone = 'Telefon je povinný';
        } else {
            // Kombinace prefixu a čísla pro validaci
            const fullPhone = (formData.value.phonePrefix || '+420') + formData.value.phone.replace(/\s/g, '');
            if (!validatePhone(fullPhone)) {
                errors.phone = 'Neplatný formát telefonního čísla';
            }
        }

        // Fakturační údaje - kontrola, zda jsou povinné
        const isPickup = formData.value.deliveryType === 'pickup';
        // V simple režimu nejsou adresní údaje povinné při pickup, v advanced jsou vždy povinné
        const addressRequired = checkoutType === 'advanced' || !isPickup;

        if (addressRequired) {
            if (!formData.value.street.trim()) {
                errors.street = 'Ulice je povinná';
            }
            if (!formData.value.city.trim()) {
                errors.city = 'Město je povinné';
            }
            if (!formData.value.zip.trim()) {
                errors.zip = 'PSČ je povinné';
            } else if (!validateZip(formData.value.zip)) {
                errors.zip = 'PSČ musí obsahovat 5 číslic';
            }
        } else {
            // I když není povinné, pokud je vyplněno, validovat formát
            if (formData.value.zip && !validateZip(formData.value.zip)) {
                errors.zip = 'PSČ musí obsahovat 5 číslic';
            }
        }

        // Firemní údaje (pokud je zaškrtnuto)
        if (formData.value.isCompany) {
            if (!formData.value.companyName.trim()) {
                errors.companyName = 'Název firmy je povinný';
            }
            if (!formData.value.companyId.trim()) {
                errors.companyId = 'IČO je povinné';
            } else if (!validateCompanyId(formData.value.companyId)) {
                errors.companyId = 'IČO musí obsahovat 8 číslic';
            }
            if (formData.value.companyVatId && !validateVatId(formData.value.companyVatId)) {
                errors.companyVatId = 'DIČ musí být ve formátu CZ12345678';
            }
        }

        // Dodací údaje (pokud je zaškrtnuto)
        if (formData.value.hasDeliveryAddress) {
            if (!formData.value.deliveryFirstName.trim()) {
                errors.deliveryFirstName = 'Jméno je povinné';
            }
            if (!formData.value.deliveryLastName.trim()) {
                errors.deliveryLastName = 'Příjmení je povinné';
            }
            if (!formData.value.deliveryStreet.trim()) {
                errors.deliveryStreet = 'Ulice je povinná';
            }
            if (!formData.value.deliveryCity.trim()) {
                errors.deliveryCity = 'Město je povinné';
            }
            if (!formData.value.deliveryZip.trim()) {
                errors.deliveryZip = 'PSČ je povinné';
            } else if (!validateZip(formData.value.deliveryZip)) {
                errors.deliveryZip = 'PSČ musí obsahovat 5 číslic';
            }
        }

        return {
            isValid: Object.keys(errors).length === 0,
            errors,
        };
    };

    return {
        formData,
        resetForm,
        fillSampleData,
        fillUserData,
        validateEmail,
        validatePhone,
        validateCompanyId,
        validateVatId,
        validateZip,
        validateForm,
    };
}

