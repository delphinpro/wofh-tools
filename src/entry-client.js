/*!
 * WofhTools
 * File: entry-client.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import '@/styles/main.scss';
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css';
import Vue from 'vue';
import Toast from 'cxlt-vue2-toastr';
import { createApp } from '@/main';
import { AxiosWrapper } from '@/utils/AxiosWrapper';


const toastConfigs = {
    position: 'top right',
    closeButton: true,
    progressBar: true,
    type: 'success',
    showMethod: 'lightSpeedIn',
    hideMethod: 'slideOutRight',
    showDuration: 400,
    hideDuration: 400,
    timeOut: 100000,
};

Vue.use(Toast, toastConfigs);
Vue.use(AxiosWrapper);

const { app } = createApp();

app.$mount('#app');

if (process.env.NODE_ENV === 'development') {
    const Debugger = () => import('@/components/Debugger/Debugger');
    const dbg = document.createElement('div');
    dbg.setAttribute('id', 'debugger');
    document.documentElement.appendChild(dbg);

    new Vue({
        el: '#debugger',
        render: h => h(Debugger),
    });
}
