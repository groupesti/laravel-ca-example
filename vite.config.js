import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import tailwindcss from "@tailwindcss/vite"

export default defineConfig({
    build: {
        cssCodeSplit: true,
        chunkSizeWarningLimit: 1600,
        rollupOptions: {
            output: {
                chunkFileNames: "js/[name]-[hash].js",
                entryFileNames: "js/[name]-[hash].js",
                assetFileNames: (assetInfo) => {
                    const name = assetInfo.names?.[0] || "asset"
                    const info = name.split(".")
                    const extType = info[info.length - 1]

                    if (/\.(png|jpe?g|gif|svg|webp|webm)$/i.test(name)) return `image/[name]-[hash].${extType}`
                    if (/\.(wav|mp3)$/i.test(name)) return `audio/[name]-[hash].${extType}`
                    if (/\.(mp4|m4p|m4v|mpg|mp2|mpeg|mpe|mpv|mkv|m2v|avi|wmv)$/i.test(name)) return `video/[name]-[hash].${extType}`
                    if (/\.(css)$/i.test(name)) return `css/[name]-[hash].${extType}`
                    if (/\.(woff|woff2|eot|ttf|otf)$/i.test(name)) return `font/[name]-[hash].${extType}`
                    if (/\.(json)$/i.test(name)) return `json/[name]-[hash].${extType}`

                    return `[name]-[hash].${extType}`
                },
                manualChunks(id) {
                    if (!id.includes("node_modules")) return

                    if (id.includes("echarts") || id.includes("zrender")) return "charts-echarts"
                    if (id.includes("apexcharts") || id.includes("chart.js")) return "charts"
                    if (id.includes("prismjs")) return "prism"
                    if (id.includes("flatpickr")) return "forms"

                    if (
                        id.includes("preline") ||
                        id.includes("simplebar") ||
                        id.includes("moment")
                    ) {
                        return "vendor-core"
                    }
                },
            },
        },
    },

    plugins: [
        laravel({
            input: [
                "resources/js/vendor.js",
                "resources/js/app.js",
                "resources/js/pages/auth-password.js",
                "resources/js/pages/auth-two-factor.js",
                "resources/js/pages/custom-table.js",
                "resources/js/pages/dashboard-ecommerce.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],

    optimizeDeps: {
        include: [
            "preline",
            "simplebar",
            "moment",
            "apexcharts",
            "chart.js/auto",
            "echarts",
            "prismjs",
            "flatpickr",
        ],
    },

    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
})
