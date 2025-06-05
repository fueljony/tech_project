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
            host: "127.0.0.1",
            port: 3001,
            strictPort: true,
            hmr: {
                host: "127.0.0.1",
                port: 3000,
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
