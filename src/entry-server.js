/*!
 * WofhTools
 * File: entry-server.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 *
 * @external    __PRELOAD_STATE__
 */

import { createApp } from '@/main';


const { app, router } = createApp(__PRELOAD_STATE__);

router.onReady(() => {

    renderVueComponentToString(app, (err, res) => {

        if (err) throw new Error(err);
        print(res);

    });

});
