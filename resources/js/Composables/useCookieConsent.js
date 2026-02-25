import { run as initCookieConsent, reset, show } from 'vanilla-cookieconsent';
import 'vanilla-cookieconsent/dist/cookieconsent.css';

export function useCookieConsent() {
    const init = async () => {
        try {
            // Pro testování - vymaž cookie pokud existuje
            // Odkomentuj následující řádek pro reset cookie při každém načtení stránky
            // reset(true);

            await initCookieConsent({
                // Automaticky zobrazit při první návštěvě
                autoShow: true,

                // Zajistíme, že se modal zobrazí i když už existuje cookie (pro testování)
                // V produkci toto nechte false
                // hideFromBots: false,

            // Kategorie cookies
            categories: {
                necessary: {
                    enabled: true,
                    readOnly: true
                },
                analytics: {
                    enabled: false,
                    readOnly: false
                },
                marketing: {
                    enabled: false,
                    readOnly: false
                }
            },

            // Jazyk - čeština
            language: {
                default: 'cs',
                translations: {
                    cs: {
                        consentModal: {
                            title: 'Nastavení cookies',
                            description: 'Máte rádi sušenky 🍪? Nic se nemá přehánět, ale i tento web jich pár potřebuje, abychom věděli, jestli nám vše funguje a kolik vás tu je. Když kliknete na Přijmout vše, uděláte nám radost. <a href="#" data-cc="show-preferencesModal">Další informace</a>',
                            acceptAllBtn: 'Přijmout vše',
                            acceptNecessaryBtn: 'Přijmout pouze nezbytné',
                            showPreferencesBtn: 'Spravovat individuální nastavení'
                        },
                        preferencesModal: {
                            title: 'Nastavení cookies',
                            acceptAllBtn: 'Přijmout vše',
                            acceptNecessaryBtn: 'Přijmout pouze nezbytné',
                            savePreferencesBtn: 'Uložit nastavení',
                            closeIconLabel: 'Zavřít',
                            sections: [
                                {
                                    title: 'Používání cookies',
                                    description: 'Cookies jsou malé textové soubory, které se ukládají do vašeho zařízení a pomáhají nám zlepšovat funkčnost webu.'
                                },
                                {
                                    title: 'Nezbytné cookies',
                                    description: 'Tyto cookies jsou nutné pro správné fungování webu. Bez nich by některé funkce nefungovaly správně.',
                                    linkedCategory: 'necessary'
                                },
                                {
                                    title: 'Analytické cookies',
                                    description: 'Tyto cookies nám pomáhají pochopit, jak návštěvníci používají náš web, abychom ho mohli zlepšovat.',
                                    linkedCategory: 'analytics'
                                },
                                {
                                    title: 'Marketingové cookies',
                                    description: 'Tyto cookies se používají pro zobrazování reklam a sledování účinnosti marketingových kampaní.',
                                    linkedCategory: 'marketing'
                                }
                            ]
                        }
                    }
                }
            },

            // GUI nastavení
            guiOptions: {
                consentModal: {
                    layout: 'cloud',
                    position: 'bottom center',
                    transition: 'slide'
                },
                preferencesModal: {
                    layout: 'box',
                    transition: 'slide'
                }
            },

            // Callbacky
            onFirstConsent: ({ cookie }) => {
                console.log('onFirstConsent fired', cookie);
            },

            onConsent: ({ changedCategories }) => {
                console.log('onConsent fired', changedCategories);
            },

            onChange: ({ changedCategories }) => {
                console.log('onChange fired', changedCategories);
            },

            // Debug callbacky
            onModalShow: ({ modalName }) => {
                console.log('Modal shown:', modalName);
            },

            onModalReady: ({ modal }) => {
                console.log('Modal ready:', modal);
            }
        });

        // Pro testování - zobraz modal ručně (odkomentuj pokud se nezobrazuje)
        // setTimeout(() => {
        //     show();
        // }, 1000);

        } catch (error) {
            console.error('CookieConsent initialization error:', error);
        }
    };

    return {
        initCookieConsent: init,
        resetCookieConsent: () => reset(true),
        showCookieConsent: () => show()
    };
}

