import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { glob } from "glob";

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
const imageFiles = GetFilesArray("resources/assets/img/**/*.{png,jpg,jpeg,gif,svg}");

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
                ...imageFiles,
            ],
            resolve: {
                alias: {
                    $: "jquery",
                    jQuery: "jquery",
                    "window.jQuery": "jquery",
                    "~": "/node_modules",
                    "@": "/resources/js",
                    "@assets": "/resources/assets",
                    "@images": "/resources/assets/img",
                },
            },
            refresh: true,
        }),
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
        cssCodeSplit: true, // Ensure CSS is split and loaded properly
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
