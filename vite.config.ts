import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { fileURLToPath } from 'node:url';

export default defineConfig({
    plugins: [
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: [
                'resources/js/app.ts',

                // Filament admin panel theme — wired via ->viteTheme() in AdminPanelProvider
                'resources/css/filament/admin/theme.css',

                'resources/css/app.css',
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
        extensions: ['.js', '.ts', '.vue', '.json'],
    },

    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
