/*!
 * WofhTools
 * File: main.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { createRouter } from '@/router';
import store from '@/store';
import App from '@/components/App/App.vue';
import SvgIcon from '@/components/App/SvgIcon';
import PageHeader from '@/components/App/PageHeader';


Vue.config.productionTip = false;

export function createApp(context = null) {

    //==
    //== Global Components
    //== ======================================= ==//

    Vue.component('PageHeader', PageHeader);
    Vue.component('SvgIcon', SvgIcon);


    const router = createRouter();

    if (context) {
        router.push({ path: context.URL });
        //App.propsData = context.STATE;
    }

    const app = new Vue({
        router,
        store,
        render: h => h(App),
    });

    return {
        app,
        router,
    };
}
