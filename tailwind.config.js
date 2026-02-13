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
            colors: {
                // These match the reference repo's professional palette
                brand: {
                    dark: '#0F172A',      // Deep Navy Sidebar
                    primary: '#4F46E5',   // Main Indigo
                    secondary: '#64748B', // Muted Slate text
                    surface: '#F8FAFC',   // Light Gray background
                    accent: '#F97316',    // KYC Warning Orange
                }
            },
            fontFamily: {
                // Using a more professional "Fintech" font stack
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
