import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            outDir: 'public/build',
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/filament/dashboard/theme.scss',
            ],
            refresh: true,
        }),
    ],
    server: {
        cors: true,
    },
});
