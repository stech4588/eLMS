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

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    'bg-primary': '#0F212E',
                    'bg-secondary': '#1A2C38',
                    'bg-tertiary': '#293E4C',
                    'text-primary': '#ffffff',
                    'text-secondary': '#e0e0e0',
                    'text-tertiary': '#a0a0a0',
                    'border-primary': '#404040',
                    'border-secondary': '#505050',
                    'accent-primary': '#38B6FF',
                    'accent-secondary': '#4CCAFF',
                    'input-bg': '#2d2d2d',
                    'input-border': '#404040',
                    'input-text': '#ffffff',
                    'button-bg': '#38B6FF',
                    'button-hover': '#4CCAFF',
                    'button-text': '#ffffff',
                    'card-bg': '#2d2d2d',
                    'card-border': '#404040',
                    'link-text': '#38B6FF',
                    'link-hover': '#4CCAFF',
                    'error-text': '#ff6b6b',
                    'success-text': '#51cf66',
                    'warning-text': '#ffd43b',
                    'info-text': '#38B6FF',
                },
                light: {
                    'bg-primary': '#ffffff',
                    'bg-secondary': '#f5f5f5',
                    'bg-tertiary': '#e5e5e5',
                    'text-primary': '#000000',
                    'text-secondary': '#333333',
                    'text-tertiary': '#666666',
                    'border-primary': '#e0e0e0',
                    'border-secondary': '#d0d0d0',
                    'accent-primary': '#38B6FF',
                    'accent-secondary': '#4CCAFF',
                    'input-bg': '#ffffff',
                    'input-border': '#e0e0e0',
                    'input-text': '#000000',
                    'button-bg': '#38B6FF',
                    'button-hover': '#4CCAFF',
                    'button-text': '#ffffff',
                    'card-bg': '#ffffff',
                    'card-border': '#e0e0e0',
                    'link-text': '#38B6FF',
                    'link-hover': '#4CCAFF',
                    'error-text': '#dc2626',
                    'success-text': '#16a34a',
                    'warning-text': '#d97706',
                    'info-text': '#0284c7',
                }
            },
            boxShadow: {
                'dark': '0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2)',
                'light': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            },
            backgroundColor: {
                'dark-overlay': 'rgba(0, 0, 0, 0.5)',
                'light-overlay': 'rgba(255, 255, 255, 0.5)',
            }
        },
    },

    plugins: [forms],
};
