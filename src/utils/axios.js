/*!
 * WofhTools
 * (c) 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import { CONSOLE_WARN } from '@/constants.js';
import { isDev } from '@/utils/index.js';

/**
 * Vue Axios plugin
 *
 * author:     https://github.com/imcvampire
 * repository: https://github.com/imcvampire/vue-axios
 * licensed    under the MIT license
 *
 * @param Vue
 * @param axios
 */
export default function plugin(Vue, axios) {

  if (plugin.installed) {
    return;
  }

  plugin.installed = true;

  if (!axios) {
    console.error('You have to install axios');
    return;
  }

  Vue.axios = axios;

  Object.defineProperties(Vue.prototype, {
    axios: {
      get() { return axios; },
    },
  });
}

/*==
 *== Request interceptors
 *== ======================================= ==*/

export function requestSuccess(config) {
  if (isDev()) {

    console.log(`%c<Interceptor> Request [${config.baseURL + config.url}]`, CONSOLE_WARN);

  }
  return config;
}

export function requestFailed(error) {
  if (isDev()) {

    // Todo: Do something with request error
    console.log('<Interceptor> Request Error: ', error);

  }
  return Promise.reject(error);
}

/*==
 *== Response interceptors
 *== ======================================= ==*/

export function responseSuccess(response) {
  if (isDev()) console.log('<Interceptor> Response', response);

  if (response.data.status !== false) {

    if (response.data.message) {
      Vue.$toast.success({
        title  : 'Success',
        message: response.data.message,
      });
    }

    return response.data;

  } else {

    Vue.$toast.warn({
      title  : response.data.message,
      message: `Request [${response.config.method.toUpperCase()}] ${response.config.url}`,
    });

  }

  if (isDev()) console.log('<Interceptor> Response', response.data);
  return response;
}

export function responseFailed(error) {

  let httpStatusCode = error.response.status;
  let httpStatusText = error.response.statusText;
  let title = error.response.data.message || '';
  let message = '';

  if (isDev()) {

    console.log(`%c<Interceptor> Response Error ${httpStatusCode} with message: ${error.response.data.message}`, CONSOLE_WARN);

    message = `HTTP: ${httpStatusCode} ${httpStatusText}`
      + `<br>Request: <code>[${error.config.method.toUpperCase()}] ${error.config.url}</code>`;

  }

  Vue.$toast.error({
    title,
    message,
  });

  return Promise.reject(error);
}
