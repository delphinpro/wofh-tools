/*!
 * WofhTools
 * File: entry-client.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Toast from 'cxlt-vue2-toastr';
import axios from 'axios';
import { createApp } from '@/app';
import VueAxiosBridge from '@/utils/vue-axios';
import { HTTP_HEADER_AUTHORIZATION, LS_KEY_TOKEN } from '@/constants';
import responseSuccess from '@/utils/axios-response-success';
import responseFailed from '@/utils/axios-response-failed';
import { requestFailed, requestSuccess } from '@/utils/axios-request';


//==
//== Toast
//== ======================================= ==//

Vue.use(Toast, {
  position: 'top right',
  closeButton: true,
  // progressBar: true,
  useHtml: true,
  type: 'success',
  showMethod: 'lightSpeedIn',
  hideMethod: 'slideOutRight',
  showDuration: 300,
  hideDuration: 200,
  timeOut: 10000,
});
Vue.$toast = Vue.prototype.$toast;


//==
//== Axios: create instance
//== ======================================= ==//

Vue.use(VueAxiosBridge, axios.create({
  baseURL: '/api',
  timeout: 0,
  responseType: 'json',
  responseEncoding: 'utf8',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },

  // Reject only if the status code is greater than or equal to 500
  validateStatus: status => status < 500,
}));

Vue.axios.interceptors.request.use(requestSuccess, requestFailed);
Vue.axios.interceptors.response.use(responseSuccess, responseFailed);

let token = localStorage.getItem(LS_KEY_TOKEN);
if (token) Vue.axios.defaults.headers.common[HTTP_HEADER_AUTHORIZATION] = `Bearer ${token}`;


//==
//== Main app
//== ======================================= ==//

createApp(window.__STATE__).$mount('#app');


//==
//== Debug bar
//== ======================================= ==//

// if (process.env.NODE_ENV === 'development') {
//   const Debugger = () => import('@/components/Debugger/Debugger');
//   const dbg = document.createElement('div');
//   dbg.setAttribute('id', 'debugger');
//   document.documentElement.appendChild(dbg);
//
//   new Vue({
//     el: '#debugger',
//     render: h => h(Debugger),
//   });
// }
