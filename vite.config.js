import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/pages/transaction-create-page.js",
                "resources/js/pages/transaction-edit-page.js",
                "resources/js/pages/insights-page.js",
            ],
            refresh: true,
        }),
    ],
    server: {
        host: "localhost", // use 'localhost' instead of default [::1]
        port: 5173,
        strictPort: true,
        cors: true, // enable CORS headers
        watch: {
            usePolling: true, // optional but recommended on some systems
        },
    },
});
