# Toaster - Průvodce instalací

## Jak toaster funguje v projektu

Toaster je systém pro zobrazování notifikací (toast messages) v aplikaci. Skládá se z:

1. **Composable (`useToast.js`)** - spravuje stav a logiku toastů
2. **Komponenta (`Toaster.vue`)** - zobrazuje toasty na stránce
3. **Registrace v `app.js`** - registruje komponentu a poskytuje funkce globálně

## Co musíte zkopírovat

### 1. Composable - `useToast.js`

**Soubor:** `resources/js/Composables/useToast.js`

Tento soubor obsahuje logiku pro správu toastů - vytváření, odstraňování a automatický časovač.

### 2. Komponenta - `Toaster.vue`

**Soubor:** `resources/js/Components/Toast/Toaster.vue`

Tento soubor obsahuje Vue komponentu, která zobrazuje toasty na stránce. Obsahuje i všechny styly.

**Poznámka:** `ToastContainer.vue` je duplicitní soubor se stejným obsahem - použijte pouze `Toaster.vue`.

## Kroky instalace v novém projektu

### Krok 1: Zkopírovat soubory

1. Vytvořte složku strukturu (pokud neexistuje):

    ```
    resources/js/Composables/
    resources/js/Components/Toast/
    ```

2. Zkopírujte soubory:
    - `resources/js/Composables/useToast.js` → do `resources/js/Composables/useToast.js`
    - `resources/js/Components/Toast/Toaster.vue` → do `resources/js/Components/Toast/Toaster.vue`

### Krok 2: Registrace v app.js

V souboru `resources/js/app.js` musíte přidat:

**A) Importy:**

```javascript
import { useToast } from "./Composables/useToast";
import Toaster from "./Components/Toast/Toaster.vue";
```

**B) Přidat Toaster komponentu do render funkce:**

Najděte část, kde vytváříte Vue aplikaci (typicky v `createInertiaApp` nebo `createApp`). Musíte přidat `Toaster` komponentu do renderu:

```javascript
const app = createApp({
    render: () =>
        h("div", [
            h(App, props), // vaše hlavní aplikace
            h(Toaster), // přidat toaster
        ]),
});
```

**C) Poskytnout toast globálně (volitelné, ale doporučené):**

```javascript
// Poskytni toast globálně
const toast = useToast();
app.provide("toast", toast);
app.config.globalProperties.$toast = toast;
```

### Krok 3: Použití v komponentách

V komponentách můžete použít toaster několika způsoby:

**Způsob 1: Pomocí composable (doporučeno)**

```javascript
<script setup>
    import {useToast} from '@/Composables/useToast'; const toast = useToast();
    // Použití toast.success('Úspěch', 'Operace byla úspěšná');
    toast.error('Chyba', 'Něco se pokazilo'); toast.warning('Varování', 'Pozor
    na toto'); toast.info('Info', 'Důležitá informace');
    toast.primary('Primární', 'Základní zpráva');
</script>
```

**Způsob 2: Globální vlastnost (pokud jste přidali do app.js)**

```javascript
<script setup>
    // V Options API nebo pomocí getCurrentInstance const {proxy} =
    getCurrentInstance(); proxy.$toast.success('Úspěch', 'Zpráva');
</script>
```

**Způsob 3: Pomocí inject (pokud jste použili provide)**

```javascript
<script setup>
    import {inject} from 'vue'; const toast = inject('toast');
    toast.success('Úspěch', 'Zpráva');
</script>
```

## Parametry funkcí

Všechny funkce (`success`, `error`, `warning`, `info`, `primary`) mají stejné parametry:

```javascript
toast.success(title, message, (duration = 3000));
```

-   `title` (string) - Nadpis toastu
-   `message` (string) - Text zprávy
-   `duration` (number, volitelné) - Doba zobrazení v ms (výchozí: 3000ms). Nastavte `0` pro permanentní toast.

## Funkce

-   `toast.success(title, message, duration)` - Zelený toast pro úspěch
-   `toast.error(title, message, duration)` - Červený toast pro chyby
-   `toast.warning(title, message, duration)` - Žlutý toast pro varování
-   `toast.info(title, message, duration)` - Modrý toast pro informace
-   `toast.primary(title, message, duration)` - Tmavý toast pro primární zprávy
-   `toast.show(type, title, message, duration)` - Obecná funkce (type: 'success', 'warning', 'danger', 'info', 'primary')
-   `toast.remove(id)` - Ruční odstranění toastu podle ID
-   `toast.clear()` - Odstraní všechny toasty

## Vlastnosti

-   Automatické odstraňování po uplynutí času
-   Progress bar ukazující zbývající čas
-   Maximálně 3 toasty najednou (starší se automaticky odstraní)
-   Kliknutím na toast ho odstraníte
-   Responzivní design (na mobilu se přizpůsobí)
-   Animace při přidání/odstranění

## Závislosti

-   Vue 3 (reactive, Teleport, TransitionGroup)
-   Žádné externí knihovny!

## Příklad kompletního použití

```vue
<template>
    <div>
        <button @click="showSuccess">Zobrazit úspěch</button>
        <button @click="showError">Zobrazit chybu</button>
    </div>
</template>

<script setup>
import { useToast } from "@/Composables/useToast";

const toast = useToast();

const showSuccess = () => {
    toast.success("Hotovo!", "Operace byla úspěšně dokončena", 5000);
};

const showError = () => {
    toast.error("Chyba!", "Něco se pokazilo", 4000);
};
</script>
```

## Kontrolní seznam instalace

-   [ ] Zkopírován soubor `useToast.js` do `resources/js/Composables/`
-   [ ] Zkopírován soubor `Toaster.vue` do `resources/js/Components/Toast/`
-   [ ] Přidány importy do `app.js`
-   [ ] Přidána komponenta `Toaster` do render funkce v `app.js`
-   [ ] (Volitelné) Přidáno globální poskytnutí toast funkcionality
-   [ ] Otestováno použití v některé komponentě

Po těchto krocích by měl toaster fungovat ve vašem projektu!
