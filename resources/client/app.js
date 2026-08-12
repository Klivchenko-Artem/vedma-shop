import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import AppLayout from '@/AppLayout.vue';

createInertiaApp({
    progress: {
        delay: 0,
        color: '#c9a15a',
    },
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob('./pages/**/*.vue')
        );
        page.default.layout ??= AppLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
