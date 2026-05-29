import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { glob } from "glob";
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';

function GetFilesArray(query) {
    return glob.sync(query);
}
/**
 * Js Files
 */
// Page JS Files
const pageJsFiles = GetFilesArray("resources/assets/js/*.js");
const utilsJsFiles = GetFilesArray("resources/js/utils/*.js");

// Processing Vendor JS Files
const vendorJsFiles = GetFilesArray("resources/assets/vendor/js/*.js");
const vendorCssFiles = GetFilesArray("resources/assets/vendor/css/pages/*.css");
const vendorCoreCssFiles = GetFilesArray("resources/assets/vendor/css/*.css");

// Processing Libs JS Files
const LibsJsFiles = GetFilesArray("resources/assets/vendor/libs/**/*.js");

/**
 * Scss Files
 */
// Processing Core, Themes & Pages Scss Files
const CoreScssFiles = GetFilesArray(
    "resources/assets/vendor/scss/**/!(_)*.scss"
);

// Processing Libs Scss & Css Files
const LibsScssFiles = GetFilesArray(
    "resources/assets/vendor/libs/**/!(_)*.scss"
);
const LibsCssFiles = GetFilesArray("resources/assets/vendor/libs/**/*.css");

// Processing Fonts Scss Files
const FontsScssFiles = GetFilesArray(
    "resources/assets/vendor/fonts/!(_)*.scss"
);

const imgFiles = GetFilesArray("resources/assets/img/**/*.{png,jpg,jpeg,gif,svg}");
const imageFiles = GetFilesArray("resources/assets/images/**/*.{png,jpg,jpeg,gif,svg}");


export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                api: "modern-compiler", // or "modern"
            },
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/stylesheets/style.css",
                "resources/css/app.css",
                "resources/assets/css/demo.css",
                "resources/js/app.js",
                "resources/js/laravel-datatables.js",
                ...pageJsFiles,
                ...vendorJsFiles,
                ...vendorCssFiles,
                ...vendorCoreCssFiles,
                ...LibsJsFiles,
                ...utilsJsFiles,
                ...CoreScssFiles,
                ...LibsScssFiles,
                ...LibsCssFiles,
                ...FontsScssFiles,
                ...imgFiles,
                ...imageFiles,
            ],
            resolve: {
                alias: {
                    $: "jquery",
                    jQuery: "jquery",
                    "window.jQuery": "jquery",
                    "~": "/node_modules",
                    '@': '/resources/js',
                    "@assets": "/resources/assets",
                    '@img': '/resources/assets/img', // Alias for img directory
                    '@images': '/resources/assets/images', // Alias for images directory
                },
            },
            refresh: true,
        }),
        // ViteImageOptimizer({
        //     png: {
        //         quality: 100,
        //     },
        //     jpeg: {
        //         quality: 50,
        //     },
        //     jpg: {
        //         quality: 50,
        //     },
        //     tiff: {
        //         quality: 100,
        //     },
        //     gif: {},
        //     webp: {
        //         lossless: true,
        //     },
        //     avif: {
        //         lossless: true,
        //     },
        //     cache: false,
        //     cacheLocation: undefined,
        // }),

    ],
    optimizeDeps: {
        include: [
          'jquery',
          'toastr',
          'datatables.net',
          'datatables.net-bs5',
          'laravel-datatables-vite', // Add all problematic CommonJS deps here
        ],
        exclude: [], // Avoid excluding anything unless necessary
      },
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'assets/css/[name]-[hash][extname]'; // Consistent CSS output
                    }
                    let extType = assetInfo.name.split('.')[1];
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'img';
                    }
                    return `assets/${extType}/[name]-[hash][extname]`;
                },
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
            },
        },
    },
    assetsInclude: [
        "**/*.woff",
        "**/*.woff2",
        "**/*.ttf",
        "**/*.eot",
        "**/*.svg",
        "**/*.png",
        "**/*.jpg",
        "**/*.jpeg",
        "**/*.gif",
    ],
});
