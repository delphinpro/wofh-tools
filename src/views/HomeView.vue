<script>/*!
 * WofhTools
 * View: HomeView.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */


import { mapGetters } from 'vuex';
import { WORLDS_LIST } from '@/store/modules/store-stat';
import { cbSortWorldsByStarted } from '@/utils';


export default {
    name: 'Home',

    data: () => ({}),

    computed: {
        ...mapGetters([
            'activeWorlds',
        ]),

        worlds: {
            get() {
                return this.activeWorlds.sort(cbSortWorldsByStarted);
            },
        },
    },

    mounted() {
        this.$store.dispatch(WORLDS_LIST);
    },

    methods: {},
};
</script>

<template>
    <div class="container pb-2">
        <PageHeader title="Действующие миры Путей истории"/>

        <div v-if="worlds.length">
            <table class="table">
                <thead>
                <tr>
                    <th class="text-left" colspan="2">Мир</th>
                    <th class="text-left">Название</th>
                    <th class="text-center">Регистрация</th>
                    <th class="text-right">Старт</th>
                    <th class="text-right">Длительность</th>
                    <th class="text-right">Статистика</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="world in worlds">
                    <td style="width: 20px; padding-right: 0;">
                        <SvgIcon
                            viewBox="0 0 512 341.4"
                            size="20"
                            :height="20*341.4/512"
                            :name="world.flag"
                        />
                    </td>
                    <td>{{world.signU}}</td>
                    <td>{{world.title}}</td>
                    <td class="text-center" :class="world['can_reg']?'text-green':'text-red'">
                        <SvgIcon :name="world['can_reg']?'check':'cross'" :size="world['can_reg']?'1rem':'0.75rem'"/>
                    </td>
                    <td class="text-right">{{world.fmtStarted}}</td>
                    <td class="text-right">{{world['fmtAge']}}</td>
                    <td class="text-right">
                        <a href="#" v-if="world.fmtUpdatedStat">{{world.fmtUpdatedStat}}</a>
                        <div v-else>Отсутствует
                            <SvgIcon name="cross" size="0.75rem"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <Alert title="Нет данных о действующих мирах" v-else></Alert>
    </div>
</template>
