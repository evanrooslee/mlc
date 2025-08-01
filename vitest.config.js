import { defineConfig } from "vitest/config";

export default defineConfig({
    test: {
        environment: "jsdom",
        globals: true,
        setupFiles: ["./tests/frontend/setup.js"],
    },
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
