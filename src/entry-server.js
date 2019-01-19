/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
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
