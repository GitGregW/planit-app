import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    safelist: [
        'col-[1_/_span_1]',
        'col-[1_/_span_2]',
        'col-[1_/_span_3]',
        'col-[1_/_span_4]',
        'col-[1_/_span_5]',
        'col-[1_/_span_6]',
      ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        fontFamily: {
            'custom': ['Bungee', 'sans-serif'],
        }
    },

    plugins: [forms],
};
