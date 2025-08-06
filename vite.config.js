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
                "resources/js/student_attendance.js",
            ],
            refresh: true,
        }),
    ],
    server: {
        proxy: {
            "/app": ["http://127.0.0.1:8000", "http://localhost/"], // Proxy Laravel requests to your Laravel app
        },
        cors: {
            origin: ["http://127.0.0.1:8000", "http://localhost"], // Allow your Laravel app to access the Vite server
            methods: ["GET", "POST", "PUT", "DELETE"],
            allowedHeaders: ["Content-Type", "Authorization"],
        },
    },
});
