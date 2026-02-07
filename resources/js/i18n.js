import { createI18n } from 'vue-i18n';

// Import JSON souborů s překlady
import cs from './Lang/cs.json';
import en from './Lang/en.json';

// Funkce pro získání výchozího jazyka
const getDefaultLocale = () => {
  // Můžete nastavit jazyk podle preferencí uživatele z localStorage
  const storedLocale = localStorage.getItem('locale');
  if (storedLocale) {
    return storedLocale;
  }

  // Nebo použít jazyk prohlížeče
  const browserLocale = navigator.language.split('-')[0];
  return ['cs', 'en'].includes(browserLocale) ? browserLocale : 'cs';
};

// Vytvoření instance i18n
const i18n = createI18n({
  legacy: false, // Použití Composition API
  locale: getDefaultLocale(), // Výchozí jazyk
  fallbackLocale: 'cs', // Záložní jazyk
  messages: {
    cs,
    en
  },
  // Další možnosti konfigurace
  globalInjection: true, // Umožní používat $t() globálně
  missingWarn: false, // Vypne varování o chybějících překladech (volitelné)
  fallbackWarn: false // Vypne varování o použití fallback jazyka (volitelné)
});

export default i18n;

// Export funkce pro změnu jazyka
export const changeLocale = (locale) => {
  i18n.global.locale.value = locale;
  localStorage.setItem('locale', locale);
  document.documentElement.lang = locale;
};

// Export funkce pro získání aktuálního jazyka
export const getCurrentLocale = () => {
  return i18n.global.locale.value;
};

// Export funkce pro překlad (pro použití v utility souborech)
export const trans = (key, params = {}) => {
  return i18n.global.t(key, params);
};

// Alias pro trans (můžete použít buď trans nebo t)
export const t = trans;

