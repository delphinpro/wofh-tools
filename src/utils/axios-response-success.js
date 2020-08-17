/*!
 * WofhTools
 * (c) 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';


export default function responseSuccess(response) {

  console.log(response);
  if (response.data.status !== false) {

    if (response.data.message) {
      Vue.$toast.success({
        title: 'Success',
        message: response.data.message,
      });
    }

    return response.data.payload;

  } else {

    Vue.$toast.warn({
      title: response.data.message,
      message: `Request [${response.config.method.toUpperCase()}] ${response.config.url}`,
    });

  }

  console.log('<Interceptor> Response', response.data);
  return response;
}
