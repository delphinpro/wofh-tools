/*!
 * WofhTools
 * (c) 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import trimRootPath from '@/utils/trimRootPath.js';
import { CONSOLE_WARN, CONSOLE_DANGER, CONSOLE_INFO } from '@/constants.js';
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
  if (isDev()) console.log('<Interceptor> Response',response);

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
  let title = `${httpStatusCode} ${httpStatusText}`;
  let message = '';

  if (isDev()) {

    console.log(`%c<Interceptor> Response Error ${httpStatusCode} with message: ${error.response.data.message}`, CONSOLE_WARN);

    message = `Request [${error.config.method.toUpperCase()}] ${error.config.url}`;

    let err = {};
    if (error.response.data.exception) {
      err = error.response.data.exception[0];
    } else if (error.response.data.error) err = error.response.data.error[0];

    if (err.message) {
      err.file = trimRootPath(err.file);
      err.trace = err.trace.map(trimRootPath);
      message += `<br>${error.response.data.message}.`;
      message += `<br><b>${err.message}.</b><br>`;
      message += `<br>${err.file}:${err.line}.<br>`;
      message += `<br>See console.`;

      console.groupCollapsed(`%c${err.type}: ${err.message}`, CONSOLE_DANGER);
      console.log(`%cFile:    ${err.file}:${err.line}`, CONSOLE_INFO);
      err.trace.map(item => console.log(trimRootPath(item)));
      console.groupEnd();
    }
  }

  Vue.$toast.error({
    title,
    message,
  });

  return Promise.reject(error);
}
