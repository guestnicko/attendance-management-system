import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/guest.css",
                "resources/js/welcome.js",
                "resources/css/welcome.css",
                "resources/js/dashboard.js",
                "resources/js/logs.js",
                "resources/js/fines.js",
                "resources/js/students.js",
                "resources/js/events.js",
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        hmr: {
            host: '127.0.0.1',
        },
        cors: {
            origin: ["http://127.0.0.1:8000", "http://localhost:8000"],
            credentials: true,
        },
    },
});
