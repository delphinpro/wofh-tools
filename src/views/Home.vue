<script>/*!
 * WofhTools
 * View: Home.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */


import { mapGetters } from 'vuex';
import { WORLDS_LIST } from '@/store/actions';


export default {
    name: 'Home',

    data: () => ({}),

    computed: {
        ...mapGetters([
            'activeWorlds',
        ]),

        worlds: {
            get() {
                return this.activeWorlds.sort((a, b) => {
                    if (a.started === b.started) return 0;
                    return a.started < b.started ? 1 : -1;
                });
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
        <PageHeader
            title="WofhTools"
            desc=""
            :crumbs="false"
        />

        <div v-if="worlds.length">
            <h2>Действующие миры Путей истории</h2>
            <table class="table">
                <thead>
                <tr>
                    <th class="text-left" colspan="2">Мир</th>
                    <th class="text-center">Регистрация</th>
                    <th class="text-right">Старт</th>
                    <th class="text-right">Длительность</th>
                    <th class="text-right">Статистика</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="world in worlds">
                    <td>{{world.sign}}</td>
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
            <pre>{{worlds[3]}}</pre>
        </div>
        <Alert title="Нет даннных о действующих мирах" v-else></Alert>
    </div>
</template>
