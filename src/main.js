/**
 * WofhTools
 * main.js
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import '@/styles/main.scss';
import Vue from 'vue';
import { createRouter } from '@/router';
import store from '@/store';
import App from '@/components/App.vue';


Vue.config.productionTip = false;

export function createApp(context = null) {

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

// if (process.env.NODE_ENV === 'development') {
//     const Debugger = () => import('@/components/Debugger');
//     const dbg = document.createElement('div');
//     dbg.setAttribute('id', 'debugger');
//     document.documentElement.appendChild(dbg);
//
//     new Vue({
//         el: '#debugger',
//         render: h => h(Debugger),
//     });
// }
