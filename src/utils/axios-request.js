/*!
 * WofhTools
 * (c) 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { colors } from '@/utils/console-tools';


export function requestSuccess(config) {
  if (process.env.NODE_ENV === 'development') {

    console.log(`%c<Interceptor> Request [${config.baseURL + config.url}]`, colors.warn);

  }
  return config;
}

export function requestFailed(error) {
  if (process.env.NODE_ENV === 'development') {

    // Todo: Do something with request error
    console.log('<Interceptor> Request Error: ', error);

  }
  return Promise.reject(error);
}
