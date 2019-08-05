/*!
 * WofhTools
 * File: main.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Vue from 'vue';
import FaIcon from 'vue-awesome/components/Icon';
import { createRouter } from '@/router';
import store from '@/store';
import App from '@/components/App/App.vue';
import SvgIcon from '@/components/App/SvgIcon';
import PageHeader from '@/components/App/PageHeader';
import Alert from '@/components/Widgets/Alert';
import Inputbox from '@/components/Forms/Inputbox';
import Checkbox from '@/components/Forms/Checkbox';
import RadioButton from '@/components/Forms/RadioButton';

import 'vue-awesome/icons/angle-left';
import 'vue-awesome/icons/check';
import 'vue-awesome/icons/cogs';
import 'vue-awesome/icons/envelope';
import 'vue-awesome/icons/key';
import 'vue-awesome/icons/laptop';
import 'vue-awesome/icons/lock';
import 'vue-awesome/icons/sign-in-alt';
import 'vue-awesome/icons/sign-out-alt';
import 'vue-awesome/icons/sync-alt';
import 'vue-awesome/icons/th';
import 'vue-awesome/icons/user';
import 'vue-awesome/icons/user-plus';

Vue.config.productionTip = false;

export function createApp(context = null) {

    //==
    //== Global Components
    //== ======================================= ==//

    Vue.component('FaIcon', FaIcon);
    Vue.component('PageHeader', PageHeader);
    Vue.component('SvgIcon', SvgIcon);
    Vue.component('Alert', Alert);
    Vue.component('Inputbox', Inputbox);
    Vue.component('Checkbox', Checkbox);
    Vue.component('RadioButton', RadioButton);


    const router = createRouter();

    if (context) {
        router.push({ path: context.URL });
        //App.propsData = context.STATE;
    }

    const app = new Vue({
        router,
        store,
        render: h => h(App),
    });

    return {
        app,
        router,
    };
}
