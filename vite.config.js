import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import {glob, globSync, globStream, globStreamSync, Glob} from "glob";
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/demo2/js/scripts.js',
                'resources/assets/extended/button-ajax.js',
                'resources/assets/demo2/sass/plugins.scss',
                'resources/assets/demo2/sass/style.scss',
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/sass/app.scss',
            ],
            refresh: true,
        }),
    ],  resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            "~fontawesome": path.resolve(__dirname,"node_modules/@fortawesome/fontawesome-free"),
            "~@fortawesome": path.resolve(__dirname,"node_modules/@fortawesome"),
            "~apexcharts": path.resolve(__dirname,"node_modules/apexcharts"),
            "~bootstrap-daterangepicker": path.resolve(__dirname,"node_modules/bootstrap-daterangepicker"),
            "~bootstrap-icons": path.resolve(__dirname,"node_modules/bootstrap-icons"),
            "~bootstrap-maxlength": path.resolve(__dirname,"node_modules/bootstrap-maxlength"),
            "~bootstrap-multiselectsplitter": path.resolve(__dirname,"node_modules/bootstrap-maxlength"),
            "~select2": path.resolve(__dirname,"node_modules/select2"),
            "~nouislider": path.resolve(__dirname,"node_modules/nouislider"),
            "~flatpickr": path.resolve(__dirname,"node_modules/flatpickr"),
            "~tiny-slider": path.resolve(__dirname,"node_modules/tiny-slider"),
            "~dropzone": path.resolve(__dirname,"node_modules/dropzone"),
            "~quill": path.resolve(__dirname,"node_modules/quill"),
            "~@yaireo": path.resolve(__dirname,"node_modules/@yaireo"),
            "~toastr": path.resolve(__dirname,"node_modules/toastr"),
            "~sweetalert2": path.resolve(__dirname,"node_modules/sweetalert2"),
            "~line-awesome": path.resolve(__dirname,"node_modules/line-awesome"),
            "@": "/resources"
        }
    }
});
