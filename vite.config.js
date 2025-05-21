import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    preview: {
        host: '0.0.0.0',
        port: process.env.PORT || 4173,
        allowedHosts: ['extrabackend-2.onrender.com'],
    },
});
