import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        require('./vendor/filament/support/tailwind.config.preset'),
        require('./vendor/tallstackui/tallstackui/tailwind.config.js') 
    ],
    
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // TallStackUI
        './vendor/tallstackui/tallstackui/src/**/*.php',
        './app/View/**/*.php',
        './app/Providers/AppServiceProvider.php',
        // Filament Table Builder
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'primary': {
                    default: '#3B71CA',
                    '50': '#F1F5FB',
                    '100': '#E3EBF7',
                    '200': '#C7D7F0',
                    '300': '#ABC2E8',
                    '400': '#8FAEE0',
                    '500': '#6590D5',
                    '600': '#3061AF',
                    '700': '#285192',
                    '800': '#204075',
                    '900': '#183058',
                    '950': '#183058',
                },
                'secondary': {
                    default: '#9FA6B2',
                    '50': '#F8F9F9',
                    '100': '#F1F2F4',
                    '200': '#E4E6E9',
                    '300': '#D6D9DE',
                    '400': '#C8CCD3',
                    '500': '#B3B9C2',
                    '600': '#848D9C',
                    '700': '#6B7585',
                    '800': '#565D6B',
                    '900': '#404650',
                    '950': '#404650',
                },
                'success': {
                    default: '#14A44D',
                    '50': '#EAFCF2',
                    '100': '#D6FAE4',
                    '200': '#ACF5C9',
                    '300': '#83F0AE',
                    '400': '#59EA93',
                    '500': '#1CE26B',
                    '600': '#118C42',
                    '700': '#0E7537',
                    '800': '#0C5D2C',
                    '900': '#094621',
                    '950': '#14A44D',
                },
                'danger': {
                    default: '#DC4C64',
                    '50': '#FCF2F4',
                    '100': '#FAE5E9',
                    '200': '#F5CCD3',
                    '300': '#F0B2BD',
                    '400': '#EB99A6',
                    '500': '#E37285',
                    '600': '#D42A46',
                    '700': '#B0233A',
                    '800': '#8D1C2F',
                    '900': '#6A1523',
                    '950': '#DC4C64',
                },
                'warning': {
                    default: '#E4A11B',
                    '50': '#FDF8EF',
                    '100': '#FBF2DE',
                    '200': '#F7E4BE',
                    '300': '#F4D79D',
                    '400': '#F0C97D',
                    '500': '#EAB54C',
                    '600': '#C48A17',
                    '700': '#A37313',
                    '800': '#825C0F',
                    '900': '#62450B',
                    '950': '#E4A11B',
                },
                'info': {
                    default: '#54B4D3',
                    '50': '#F3FAFC',
                    '100': '#E7F4F9',
                    '200': '#CEE9F2',
                    '300': '#B6DFEC',
                    '400': '#9ED4E6',
                    '500': '#79C4DC',
                    '600': '#34A4CA',
                    '700': '#2B89A8',
                    '800': '#236D86',
                    '900': '#1A5265',
                    '950': '#54B4D3',
                },
            },
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
    ],
};
