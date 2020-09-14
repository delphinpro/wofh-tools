<!--
  WofhTools
  View: StatisticView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import StatIndexView from '@/views/Stat/StatIndexView.vue';


// const Chart = () => import(/* webpackChunkName: "statistic" */ 'highcharts-vue');
// const darkTheme = () => import(/* webpackChunkName: "statistic" */ '@/utils/charts-dark-theme');
export default {
  name: 'StatisticView',

  components: {
    StatIndexView,
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
    <StatIndexView v-if="$route.name === 'stat'"/>
    <router-view v-else></router-view>

    <!--<Chart :options="chartOptions"/>-->
  </div>
</template>
