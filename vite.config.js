import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue2";
import laravel from "laravel-vite-plugin";
import { VuetifyResolver } from "unplugin-vue-components/resolvers";
import Components from "unplugin-vue-components/vite";

export default defineConfig(({ mode }) => {
    //const env = loadEnv(mode, process.cwd());
    //console.log(mode, process.env.NODE_ENV);
    return {
        plugins: [
            vue(),
            laravel(["resources/sass/app.scss", "resources/js/app.js"]),
            // react(),

            Components({
                // dirs: ["resources/js/admin/shared-components/base"],
                //directoryAsNamespace: false,
                //allowOverrides: false,
                resolvers: [
                    // Vuetify
                    VuetifyResolver(),
                ],
                version: 2.6,
            }),
        ],
        resolve: {
            alias: {
                // '@js': resolve(__dirname, '/resources/js'),
                // '@img': resolve(__dirname, './resources/images'),
                "@js": "/resources/js",
                "@sass": "/resources/sass",
                // "@img": "/resources/images",
                vue:
                    mode === "development"
                        ? "vue/dist/vue.js"
                        : "vue/dist/vue.min.js",
                //vue: "vue/dist/vue.min.js",
            },
            extensions: [".ts", ".js", ".vue"],
        },
        server: {
            // Listen on all interfaces for Docker/WSL2 compatibility
            host: "0.0.0.0",
            port: 3001,
            strictPort: true,
            hmr: {
                // Use VITE_HMR_HOST from .env if set, otherwise fallback to 'localhost'
                host: process.env.VITE_HMR_HOST || "localhost",
                port: 3001,
            },
        },
        css: {
            preprocessorOptions: {
                scss: {
                    // additionalData: `@import "vuetify/src/styles/styles.sass";`,
                },
            },
        },
    };
});
