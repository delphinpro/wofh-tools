<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import { Chart } from 'highcharts-vue';
import { darkTheme } from '@/utils/charts-dark-theme';


export default {
    name: 'statistic',

    components: {
        Chart,
    },

    data: () => ({
        worlds: [],
        temp: null,
    }),

    computed: {
        demo1() {
            return this.$store.getters.demoValue;
        },

        chartOptions() {
            return {
                ...darkTheme,
                ...this.$store.getters.accountsChart,
            };
        },
    },

    created() {
    },

    mounted() {
        this.listWorlds();
        this.loadStatData();
    },

    methods: {
        listWorlds() {
            this.axios
                .get('/wofh/worlds')
                .then(response => {
                    this.worlds = [...response.data.payload.worlds];
                }).catch(error => {
            });
        },
        loadStatData() {
            this.axios
                .get('/stat')
                .then(response => {
                    console.log('RESPONSE ACCOUNT', response.data.payload);
                    this.temp = response.data.payload.chart;
                    this.$store.dispatch('setChart', response.data.payload.chart);
                }).catch(error => {
            });
        },
    },
};
</script>

<template>
    <div class="container">
        <h1>Statistic</h1>

        <ul class="slist">
            <li v-for="w in worlds" v-if="w.statistic">
                <code v-text="w.sign"></code>
                <span v-text="w.title"></span>
            </li>
        </ul>

        <Chart :options="chartOptions"/>

        <p>chartOptions</p>
    </div>
</template>

<style lang="scss" scoped>
    .slist {
        list-style: none;
        padding: 0;

        li {
            display: flex;
        }

        code {
            margin-right: 1em;
        }
    }
</style>
