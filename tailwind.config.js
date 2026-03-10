import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'from-teal-500', 'from-teal-600', 'from-teal-700',
        'to-teal-600', 'to-teal-700', 'to-teal-800',
        'via-teal-600',
        'from-blue-500', 'to-blue-700',
        'from-orange-500', 'to-orange-700',
        'text-teal-600', 'text-teal-700', 'text-teal-100',
        'bg-teal-50', 'bg-teal-100',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
