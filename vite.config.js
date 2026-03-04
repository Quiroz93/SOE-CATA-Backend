import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'localhost',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/welcome.css',
                'resources/css/auth.css',
                'resources/css/admin.css',
                'resources/css/admin-crud.css',
                'resources/css/profile.css',
                'resources/css/public.css',
                'resources/css/reportes.css',
                'resources/js/app.js',
                'resources/js/admin/dashboard.js',
                'resources/js/admin/dashboard-stats.js',
                'resources/js/admin/reportes.js'
            ],
            refresh: true,
        }),
    ],
});
