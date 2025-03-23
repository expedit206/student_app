import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import axios from 'axios';

// Configuration d'Axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Déclaration des types pour Vite
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }
    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

// Nom de l'application depuis les variables d'environnement
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Initialisation de l'application Inertia
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // Montage de l'application
        vueApp.mount(el);

        // Initialisation du thème après le montage
        initializeTheme();

        // Enregistrement du Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        console.log('Service Worker enregistré:', registration);
                    })
                    .catch(error => {
                        console.error('Erreur lors de l’enregistrement du Service Worker:', error);
                    });
            });
        }
    },
    progress: {
        color: '#4B5563',
    },
});