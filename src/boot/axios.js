/*!
 * WofhTools
 * File: boot/axios.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Axios from 'axios';
import { CONSOLE_DANGER, CONSOLE_WARN } from '@/constants';

/*==
 *== Request interceptors
 *== ======================================= ==*/

function requestSuccess(config) {
  if (process.env.DEV) {
    console.log(`%c<Interceptor> Request [${config.baseURL + config.url}]`, CONSOLE_WARN);
  }
  return config;
}

function requestFailed(error) {
  if (process.env.DEV) {
    // Todo: Do something with request error
    console.log('%c<Interceptor> Request Error: ', CONSOLE_DANGER, error);
  }
  return Promise.reject(error);
}

/*==
 *== Response interceptors
 *== ======================================= ==*/

function responseSuccess(response) {
  if (process.env.DEV) console.log('<Interceptor> Response', `${response.status} ${response.statusText}`);

  if (response.data.status !== false) {

    if (response.data.message) {
      // Vue.$toast.success({
      //   title  : 'Success',
      //   message: response.data.message,
      // });
    }

    return response.data;

  } else {

    // Vue.$toast.warn({
    //   title  : response.data.message,
    //   message: `Request [${response.config.method.toUpperCase()}] ${response.config.url}`,
    // });

  }

  if (process.env.DEV) console.log('<Interceptor> Response', response.data);
  return response;
}

function responseFailed(error) {
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

  // Vue.$toast.error({
  //   title,
  //   message,
  // });

  return Promise.reject(error);
}

/*==
 *== Create instance
 *== ======================================= ==*/

export default ({ app, Vue, ssrContext }) => {

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
    validateStatus: status => status < 400,
  });

  axiosInstance.interceptors.request.use(requestSuccess, requestFailed);
  axiosInstance.interceptors.response.use(responseSuccess, responseFailed);

  Vue.axios = axiosInstance;
  app.axios = axiosInstance;
}

// let token = localStorage.getItem(LS_KEY_TOKEN);
// if (token) Vue.axios.defaults.headers.common[HTTP_HEADER_AUTHORIZATION] = `Bearer ${token}`;
