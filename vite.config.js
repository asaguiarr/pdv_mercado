import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 8113,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/pdv.css',
                'resources/js/app.js',
                'resources/js/pdv.js',
            ],
            refresh: true,
        }),
    ],
});
