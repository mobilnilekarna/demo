<template>
  <div class="container mx-auto py-8 px-4">
    <h1 class="mb-4 text-2xl font-bold text-gray-900 dark:text-gray-100">
      PlaceKit Autocomplete Demo
    </h1>

    <p class="mb-6 text-gray-600 dark:text-gray-400">
      Zadej adresu do pole níže a vyzkoušej PlaceKit autocomplete funkcionalitu.
    </p>

    <div class="max-w-2xl">
      <div class="mb-4">
        <label for="placekit-autocomplete" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Celá adresa
        </label>
        <input
          id="placekit-autocomplete"
          type="text"
          placeholder="Zadej adresu..."
          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Ulice
          </label>
          <input
            id="street"
            v-model="street"
            type="text"
            readonly
            placeholder="Ulice"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed"
          />
        </div>

        <div>
          <label for="houseNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Číslo popisné
          </label>
          <input
            id="houseNumber"
            v-model="houseNumber"
            type="text"
            readonly
            placeholder="Číslo popisné"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed"
          />
        </div>

        <div>
          <label for="postalCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            PSČ
          </label>
          <input
            id="postalCode"
            v-model="postalCode"
            type="text"
            readonly
            placeholder="PSČ"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed"
          />
        </div>

        <div>
          <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Město
          </label>
          <input
            id="city"
            v-model="city"
            type="text"
            readonly
            placeholder="Město"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed"
          />
        </div>
      </div>

      <div v-if="selectedAddress" class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
          Vybraná adresa (raw data):
        </h2>
        <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ JSON.stringify(selectedAddress, null, 2) }}</pre>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import placekitAutocomplete from '@placekit/autocomplete-js'
import '@placekit/autocomplete-js/dist/placekit-autocomplete.css'

const selectedAddress = ref(null)
const street = ref('')
const houseNumber = ref('')
const postalCode = ref('')
const city = ref('')

onMounted(() => {
  const pka = placekitAutocomplete('pk_cQPWVfUEBh4fVwBxZRlGzJNbZwWOTBMX1J+HFu//Dvk=', {
    target: '#placekit-autocomplete',
    maxResults: 10,
    types: ['street'], // Pouze ulice s čísly popisnými
    countries: ['cz'], // Omezit na Českou republiku
  })

  // Použijeme 'pick' event místo onSelect
  pka.on('pick', (value, item) => {
    console.log('PlaceKit pick event:', { value, item })

    selectedAddress.value = {
      value: value,
      item: item
    }

    // PlaceKit vrací data podle PKResult struktury:
    // - street: { number: string, suffix: string, name: string }
    // - city: string
    // - zipcode: string[] (pole PSČ)
    // - name: string (celý název)

    // Ulice - z item.street.name
    street.value = item?.street?.name || ''

    // Číslo popisné - z item.street.number (a případně suffix)
    const houseNum = item?.street?.number || ''
    const houseSuffix = item?.street?.suffix || ''
    houseNumber.value = houseNum + (houseSuffix ? ' ' + houseSuffix : '')

    // PSČ - z item.zipcode (je to pole, vezmeme první)
    postalCode.value = (item?.zipcode && item.zipcode.length > 0) ? item.zipcode[0] : ''

    // Město - z item.city
    city.value = item?.city || ''
  })
})
</script>

<style>
/* PlaceKit Autocomplete Dropdown Styles - GLOBAL (panel is created in body) */
/* Panel container */
.pka-panel {
  border-radius: 0.5rem !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  margin-top: 0.5rem !important;
}

/* Suggestions container */
.pka-panel-suggestions {
  max-height: 300px !important;
  border-radius: 0.5rem !important;
}

/* Scrollbar styling */
.pka-panel-suggestions::-webkit-scrollbar {
  width: 8px;
}

.pka-panel-suggestions::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.dark .pka-panel-suggestions::-webkit-scrollbar-track {
  background: #374151;
}

.pka-panel-suggestions::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.pka-panel-suggestions::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.dark .pka-panel-suggestions::-webkit-scrollbar-thumb {
  background: #4b5563;
}

.dark .pka-panel-suggestions::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}

/* Individual suggestion items */
.pka-panel-suggestion {
  transition: background-color 0.15s ease-in-out !important;
  border-bottom: 1px solid rgba(243, 244, 246, 0.5) !important;
}

.dark .pka-panel-suggestion {
  border-bottom-color: rgba(55, 65, 81, 0.5) !important;
}

.pka-panel-suggestion:last-child {
  border-bottom: none !important;
}

/* Hover state */
.pka-panel-suggestion:hover:not(.pka-active) {
  background-color: rgba(249, 250, 251, 0.8) !important;
}

.dark .pka-panel-suggestion:hover:not(.pka-active) {
  background-color: rgba(55, 65, 81, 0.5) !important;
}

/* Active/selected suggestion */
.pka-panel-suggestion.pka-active {
  background-color: rgb(59, 130, 246) !important;
  color: rgba(255, 255, 255, 0.9) !important;
  font-weight: 500 !important;
}

.dark .pka-panel-suggestion.pka-active {
  background-color: rgb(37, 99, 235) !important;
  color: rgba(255, 255, 255, 0.95) !important;
}

/* Selected suggestion */
.pka-panel-suggestion.pka-selected {
  background-color: rgba(59, 130, 246, 0.1) !important;
}

.dark .pka-panel-suggestion.pka-selected {
  background-color: rgba(59, 130, 246, 0.2) !important;
}

/* Suggestion label name */
.pka-panel-suggestion-label-name {
  font-weight: 500 !important;
}

.pka-panel-suggestion.pka-active .pka-panel-suggestion-label-name {
  color: rgb(255, 255, 255) !important;
  font-weight: 600 !important;
}

/* Highlight text in suggestions (mark tags) */
.pka-panel-suggestion-label-name mark {
  background: transparent !important;
  color: inherit !important;
  text-decoration: underline !important;
  text-decoration-color: rgba(59, 130, 246, 0.6) !important;
  text-underline-offset: 2px !important;
  font-weight: 600 !important;
}

.pka-panel-suggestion.pka-active .pka-panel-suggestion-label-name mark {
  text-decoration-color: rgba(255, 255, 255, 0.7) !important;
  color: rgb(255, 255, 255) !important;
}

/* Suggestion sub label */
.pka-panel-suggestion-label-sub {
  opacity: 0.7 !important;
}

.pka-panel-suggestion.pka-active .pka-panel-suggestion-label-sub {
  opacity: 0.9 !important;
  color: rgba(255, 255, 255, 0.9) !important;
}
</style>

