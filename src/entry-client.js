/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import Vue from 'vue';
import { createApp } from '@/main';


const { app } = createApp();

app.$mount('#app');

if (process.env.NODE_ENV === 'development') {
    const Debugger = () => import('@/components/Debugger');
    const dbg = document.createElement('div');
    dbg.setAttribute('id', 'debugger');
    document.documentElement.appendChild(dbg);

    new Vue({
        el: '#debugger',
        render: h => h(Debugger),
    });
}
