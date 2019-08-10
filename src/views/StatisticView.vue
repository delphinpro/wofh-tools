<script>/*!
 * WofhTools
 * View: StatisticView.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import StatHomeView from '@/views/Stat/StatHomeView';


export default {
    name: 'StatisticView',

    components: {
        StatHomeView,
    },

    data: () => ({}),

    computed: {
    },

    methods: {
    },
};
</script>

<template>
    <div class="container pb-2">
        <StatHomeView v-if="$route.name === 'stat'"></StatHomeView>
        <router-view v-else></router-view>
    </div>
</template>
