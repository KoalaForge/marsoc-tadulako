import '../css/app.css';

import { createApp, h, type DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createI18n } from 'vue-i18n';

const appName = import.meta.env.VITE_APP_NAME || 'M.R.T';

createInertiaApp({
    // The server renders the full <title> (see App\Support\Seo); SiteLayout
    // hands it back through <Head> so client navigation keeps it in sync.
    title: (title) => title || appName,
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const page = props.initialPage.props as { locale: string; translations: Record<string, unknown> };

        const i18n = createI18n({
            legacy: false,
            globalInjection: true,
            locale: page.locale,
            fallbackLocale: 'id',
            messages: {
                [page.locale]: page.translations,
            },
        });

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#003770',
        showSpinner: false,
        delay: 150,
    },
});
