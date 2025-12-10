import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/patient-cards.css',
                'resources/js/app.js',
                'resources/js/agenda.js',
                'resources/js/logs.js',
                'resources/js/citas.js',
                'resources/js/alerts.js',
                'resources/js/task-form.js',
            ],
            refresh: true,
        }),
    ],
});
