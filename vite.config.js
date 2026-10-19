import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Membuka akses untuk semua IP di jaringan
        hmr: {
            host: '192.168.100.77', // Ganti dengan IP IPv4 komputer Anda dari Langkah 1
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css'], // Cukup CSS saja
            refresh: true,
        }),
    ],
});