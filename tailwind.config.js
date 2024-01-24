import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

// Filament Table Builder
import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // Tailwind Elements
        './node_modules/tw-elements/dist/js/**/*.js',
        // Filament Table Builder
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            width: {
                '100': '25rem',
                '128': '32rem',
                '192': '48rem',
            },
            height: {
                '100': '25rem',
                '128': '32rem',
                '192': '48rem',
            },
            minHeight: {
                '100': '25rem',
                '128': '32rem',
                '192': '48rem',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    darkMode: 'class',

    plugins: [
        forms,
        typography,
        require('tw-elements/dist/plugin.cjs'),
    ],
};
