/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
         "primary": {
            "50":  "#e6f8f0",
            "100": "#c0edd9",
            "200": "#96e2c0",
            "300": "#6cd6a6",
            "400": "#45cb8f",
            "500": "#00ab59",
            "600": "#009a50",
            "700": "#008345",
            "800": "#006c39",
            "900": "#004b28"
        },
       "secondary": {
            "50":  "#fff0e8",
            "100": "#ffd6c2",
            "200": "#ffb08a",
            "300": "#ff8a52",
            "400": "#ff6a24",
            "500": "#ff4500",
            "600": "#e63e00",
            "700": "#bf3300",
            "800": "#992800",
            "900": "#661a00"
        },
        "success": "#4AC98F",
        "danger": "#ED1C24",
        "warning": "#f59e0b",
        "info": "#00A6D6",
        "neutral": {
            "50": "#f9fafb",
            "100": "#f3f4f6",
            "200": "#e5e7eb",
            "300": "#d1d5db",
            "400": "#9ca3af",
            "500": "#6b7280",
            "600": "#4b5563",
            "700": "#374151",
            "800": "#1f2937",
            "900": "#111827"
        }
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  plugins: [],
}
