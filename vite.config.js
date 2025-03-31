import {defineConfig} from 'vite';

export default defineConfig({
    build: {
        rollupOptions: {
            input: './assets/js/main.js',
        },
        outDir: 'public/dist',
        assetsDir: 'assets',
        manifest: true,
    }
});