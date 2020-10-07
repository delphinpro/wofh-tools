/*!
 * WofhTools
 * File: boot/axios.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Axios from 'axios';
import { CONSOLE_WARN } from '@/constants';

export default ({ app, Vue, ssrContext, store }) => {

  const baseURL = ssrContext ? `${process.env.VUE_APP_SSR_AXIOS_BASE_URL ?? ''}/api` : '/api';

  let axiosInstance = Axios.create({
    baseURL,
    timeout         : 0,
    responseType    : 'json',
    responseEncoding: 'utf8',
    headers         : {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept'          : 'application/json',
    },

    // Reject only if the status code is greater than or equal to specify here
    validateStatus: status => status < 500,
  });

  axiosInstance.interceptors.response.use(
    /*==
     *== Response SUCCESS
     *== ======================================= ==*/
    response => {
      let httpStatusCode = response.status;
      let endpoint = response.config.url.replace(response.config.baseURL, '');
      if (process.env.DEV) {
        console.log('<Interceptor> Response', `${httpStatusCode} ${response.statusText} [${endpoint}]`);
      }

      if (httpStatusCode >= 400 && httpStatusCode < 500) {
        if (ssrContext) {
          return Promise.reject({ code: httpStatusCode });
        } else {
          store.commit('showErrorPage', httpStatusCode);
        }
      }

      return response.data;
    },

    /*==
     *== Response FAILED
     *== ======================================= ==*/
    error => {
      let httpStatusCode = error.response?.status || '';
      let httpStatusText = error.response?.statusText || '';
      let title = error.response?.data?.message || '';
      let message = '';

      if (!httpStatusCode && httpStatusText) {
        httpStatusCode = error.code;
        title = error.message;
      }

      if (process.env.DEV) {
        console.log(`%c<Interceptor> Response Error ${httpStatusCode} with message: ${title}`, CONSOLE_WARN);
        message = `HTTP: ${httpStatusCode} ${httpStatusText}`
          + `<br>Request: <code>[${error.config.method.toUpperCase()}] ${error.config.url}</code>`;
      }

      return Promise.reject(error);
    },
  );

  Vue.axios = axiosInstance;
  app.axios = axiosInstance;
}
