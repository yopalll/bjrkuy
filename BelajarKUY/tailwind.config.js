import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#2563EB',
                secondary: '#7C3AED',
                'brand-bg-soft': '#E2E8ED',
                'brand-cream-card': '#FDF6ED',
                'brand-sidebar-light': '#F9F6F0',
                'brand-text-dark': '#2B3A4A',
                'brand-accent-blue': '#3B5973',
            },
        },
    },

    plugins: [forms],
};
