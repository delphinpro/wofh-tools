<script>/*!
 * WofhTools
 * View: Home.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */


import { ACTIVE_WORLDS } from '@/store/actions';
import { dateFormat } from '@/utils/date';


export default {
    name: 'Home',

    data: () => ({}),

    computed: {
        worlds: {
            get() {
                return this.$store.state.activeWorlds.sort((a, b) => {
                    if (a.started === b.started) return 0;
                    return a.started < b.started ? 1 : -1;
                });
            },
        },
    },

    mounted() {
        if (!this.worlds.length) {
            this.getActiveWorlds();
        }
    },

    methods: {
        async getActiveWorlds() {
            let response = await this.axios.get('/wofh/worlds/active');
            this.$store.commit(ACTIVE_WORLDS, response.data.payload.worlds);
        },

        dateFormat: (ts) => dateFormat(ts),
    },
};
</script>

<template>
    <div class="container">
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
                    <td class="text-right">{{dateFormat(world.started)}}</td>
                    <td class="text-right">{{world['fmtAge']}}</td>
                    <td class="text-right">
                        <a href="#" v-if="world['time_of_updated_stat']">
                            {{dateFormat(world['time_of_updated_stat'])}}
                        </a>
                        <div v-else>Отсутствует
                            <SvgIcon name="cross" size="0.75rem"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
