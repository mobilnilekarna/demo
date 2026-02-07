# Laravel 10 + Vue.js 3 + Inertia.js

Tento projekt je připravený setup Laravel 10 s Vue.js 3 a Inertia.js pro moderní full-stack vývoj.

## Nainstalované balíčky

### Backend (Laravel)

-   Laravel 10
-   Inertia.js Laravel adapter
-   Ziggy pro routy v JavaScriptu

### Frontend (Vue.js)

-   Vue.js 3
-   Inertia.js Vue 3 adapter
-   Vite s Vue pluginem

## Spuštění projektu

### 1. Instalace závislostí

```bash
# Backend závislosti
composer install

# Frontend závislosti
npm install
```

### 2. Konfigurace prostředí

```bash
# Zkopírujte .env.example na .env
cp .env.example .env

# Vygenerujte aplikaci klíč
php artisan key:generate
```

### 3. Sestavení frontend assets

```bash
# Pro vývoj (s hot reload)
npm run dev

# Pro produkci
npm run build
```

### 4. Spuštění serveru

```bash
# Laravel development server
php artisan serve
```

Aplikace bude dostupná na `http://localhost:8000`

## Struktura projektu

```
resources/
├── js/
│   ├── Pages/          # Vue.js komponenty (Inertia pages)
│   │   └── Welcome.vue
│   └── app.js          # Hlavní JavaScript soubor
├── css/
│   └── app.css         # CSS styly
└── views/
    └── app.blade.php   # Root template pro Inertia
```

## Vytváření nových stránek

1. Vytvořte Vue komponentu v `resources/js/Pages/`
2. Vytvořte route v `routes/web.php` s `Inertia::render()`
3. Komponenta se automaticky načte

### Příklad:

```php
// routes/web.php
Route::get('/about', function () {
    return Inertia::render('About', [
        'title' => 'O nás'
    ]);
});
```

```vue
<!-- resources/js/Pages/About.vue -->
<template>
    <div>
        <h1>{{ title }}</h1>
    </div>
</template>

<script setup>
defineProps({
    title: String,
});
</script>
```

## Užitečné příkazy

```bash
# Vytvoření nového controlleru
php artisan make:controller ExampleController

# Vytvoření nového modelu
php artisan make:model Example -m

# Spuštění migrací
php artisan migrate

# Vymazání cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Vývoj

Pro vývoj doporučujeme spustit oba servery současně:

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (s hot reload)
npm run dev
```

Tím získáte hot reload pro frontend změny a plnou funkcionalnost Laravel backendu.
