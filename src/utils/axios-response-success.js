/*!
 * WofhTools
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';


export default function responseSuccess(response) {

    if (response.data.status !== false) {

        if (response.data.message) {
            Vue.$toast.success({
                title: 'Success',
                message: response.data.message,
            });
        }

    } else {

        Vue.$toast.warn({
            title: response.data.message,
            message: `Request [${response.config.method.toUpperCase()}] ${response.config.url}`,
        });

    }

    console.log('<Interceptor> Response', response.data);
    return response;
}
