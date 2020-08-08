<script>/*!
 * WofhTools
 * View: StatisticView.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import StatHomeView from '@/views/Stat/StatHomeView';


// const Chart = () => import(/* webpackChunkName: "statistic" */ 'highcharts-vue');
// const darkTheme = () => import(/* webpackChunkName: "statistic" */ '@/utils/charts-dark-theme');
export default {
    name: 'StatisticView',

    components: {
        StatHomeView,
        // Chart,
    },

    data: () => ({}),

    computed: {
        // chartOptions() {
        //     return {
        //         ...darkTheme,
        //         ...this.$store.getters.accountsChart,
        //     };
        // },
    },

    methods: {
        // loadStatData() {
        //     this.axios
        //         .get('/stat')
        //         .then(response => {
        //             console.log('RESPONSE ACCOUNT', response.data.payload);
        //             this.temp = response.data.payload.chart;
        //             this.$store.dispatch('setChart', response.data.payload.chart);
        //         }).catch(error => {
        //     });
        // },
    },
};
</script>

<template>
    <div class="container pb-2">
        <StatHomeView v-if="$route.name === 'stat'"></StatHomeView>
        <router-view v-else></router-view>

        <!--<Chart :options="chartOptions"/>-->
    </div>
</template>
