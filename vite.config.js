/*import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});*/

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import fs from "fs";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        https: {
            key: fs.readFileSync(path.resolve(__dirname, 'certs/privkey.pem')),
            cert: fs.readFileSync(path.resolve(__dirname, 'certs/fullchain.pem'))
        },
        host: '127.0.0.1', // Cambia esto a tu dominio
        port: 5173,
        strictPort: true,
    },
});
//lapieza.do www.lapieza.do;

//https://135.148.26.113:5173/
//ssl_certificate /etc/letsencrypt/live/devs.lapieza.net/fullchain.pem;
//ssl_certificate_key /etc/letsencrypt/live/devs.lapieza.net/privkey.pem;




/*import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true,
            buildDirectory: "public/build", // Esto genera los archivos en public/build
        }),
        vue(),
    ],
    base: "/public/build/", // Establece la base del proyecto
});*/




