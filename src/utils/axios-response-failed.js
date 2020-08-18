/*!
 * WofhTools
 * (c) 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import trimRootPath from '@/utils/trimRootPath';
import { colors } from '@/utils/console-tools';


export default function responseFailed(error) {

  let httpStatusCode = error.response.status;
  let httpStatusText = error.response.statusText;
  let title = `${httpStatusCode} ${httpStatusText}`;
  let message = '';

  if (process.env.NODE_ENV === 'development') {

    console.log(`%c<Interceptor> Response Error ${httpStatusCode} with message: ${error.response.data.message}`, colors.warn);

    message = `Request [${error.config.method.toUpperCase()}] ${error.config.url}`;

    let err = {};
    if (error.response.data.exception) err = error.response.data.exception[0];
    else if (error.response.data.error) err = error.response.data.error[0];

    if (err.message) {
      err.file = trimRootPath(err.file);
      err.trace = err.trace.map(trimRootPath);
      message += `<br>${error.response.data.message}.`;
      message += `<br><b>${err.message}.</b><br>`;
      message += `<br>${err.file}:${err.line}.<br>`;
      message += `<br>See console.`;

      console.groupCollapsed(`%c${err.type}: ${err.message}`, colors.danger);
      console.log(`%cFile:    ${err.file}:${err.line}`, colors.info);
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
