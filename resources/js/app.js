import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import HighchartsVue from "highcharts-vue";
import PrimeVue from "primevue/config";
import Lara from "@/primevue/presets/lara";
import Aura from "@primevue/themes/aura";
import {LoadingPlugin} from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css'
import ToastService from "primevue/toastservice";

import "primeicons/primeicons.css";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(HighchartsVue)
            .use(LoadingPlugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                },
                pt: {
                    global: {
                        css: `
                            .my-button {
                                border-width: 2px;
                            },
                            ::v-deep(.p-breadcrumb) {
                                background: transparent !important;
                                box-shadow: none !important;
                            }
                        `,
                    },
                    button: {
                        root: "my-button",
                    },
                },
            })

            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: "#ebe300",
        // color: "#4B5563",
    },
});

// createInertiaApp({
//     title: (title) => `${title} - ${appName}`,
//     resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
//     setup({ el, App, props, plugin }) {
//         return createApp({ render: () => h(App, props) })
//             .use(plugin)
//             .use(ZiggyVue)
//             .mount(el);
//     },
//     progress: {
//         color: '#4B5563',
//     },
// });
