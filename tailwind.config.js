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
                app1: '#dc4125',
                app2: '#fff4e2',
                app3: '#096237',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'fade-out': {
                    '0%': { opacity: '1' },
                    '100%': { opacity: '0' },
                }
            },
            animation: {
                'fade-in': 'fade-in 0.2s ease-out',
                'fade-out': 'fade-out 0.2s ease-out'
            }
        },
    },

    plugins: [forms],
};
