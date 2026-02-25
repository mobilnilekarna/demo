import { useI18n as vueUseI18n } from 'vue-i18n';
import { computed } from 'vue';

/**
 * Composable pro práci s i18n
 */
export const useI18n = () => {
  const { t, locale, availableLocales } = vueUseI18n();

  // Funkce pro změnu jazyka
  const changeLocale = (newLocale) => {
    if (availableLocales.includes(newLocale)) {
      locale.value = newLocale;
      localStorage.setItem('locale', newLocale);
      document.documentElement.lang = newLocale;
    }
  };

  // Aktuální jazyk
  const currentLocale = computed(() => locale.value);

  // Dostupné jazyky
  const locales = computed(() => availableLocales);

  return {
    t, // Funkce pro překlad
    locale, // Reaktivní hodnota aktuálního jazyka
    currentLocale, // Computed hodnota aktuálního jazyka
    changeLocale, // Funkce pro změnu jazyka
    locales, // Dostupné jazyky
    availableLocales
  };
};

