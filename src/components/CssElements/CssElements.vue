<script>/*!
 * WofhTools
 * CssElements.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import DemoPalette from '@/components/CssElements/DemoPalette';
import DemoType from '@/components/CssElements/DemoType';
import DemoIcons from '@/components/CssElements/DemoIcons';
import DemoButtons from '@/components/CssElements/DemoButtons';
import DemoInputs from '@/components/CssElements/DemoInputs';
import DemoBoxes from '@/components/CssElements/DemoBoxes';
import DemoAlerts from '@/components/CssElements/DemoAlerts';


export default {
    name: 'CssElements',

    components: {
        DemoPalette,
        DemoType,
        DemoIcons,
        DemoButtons,
        DemoInputs,
        DemoBoxes,
        DemoAlerts,
    },

    computed: {
        view() {
            let name = this.$route.params.id[0].toUpperCase() + this.$route.params.id.toString().substring(1);
            return `Demo${name}`;
        },
    },
};
</script>

<template>
    <div>
        <h1>{{view}}</h1>
        <component :is="view"></component>
    </div>
</template>
