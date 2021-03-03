import AppLayout from '@/Layouts/AppLayout';

require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) =>
                import(`./Pages/${name}`).then(({ default: page }) => {
                    if (page.layout === undefined && name !== 'Welcome' && !name.includes('Auth')) {
                        page.layout = AppLayout;
                    }
                    return page;
                }),
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
