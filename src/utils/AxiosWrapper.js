/*!
 * WofhTools
 * File: utils/AxiosWrapper.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import axios from 'axios';


export function AxiosWrapper(Vue) {

    const axiosInstance = axios.create({
        baseURL: '/api',
        timeout: 0,
        responseType: 'json',
        responseEncoding: 'utf8',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    Object.defineProperty(Vue.prototype, 'axios', {
        get() {
            return axiosInstance;
        },
    });
}
