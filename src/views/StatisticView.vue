<script>/*!
 * WofhTools
 * View: StatisticView.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { mapGetters } from 'vuex';
import InfoBox from '@/components/Widgets/InfoBox';
import { WORLDS_LIST } from '@/store/actions';
import { cbSortWorldsByStarted } from '@/utils';


export default {
    name: 'statistic',

    components: {
        InfoBox,
    },

    data: () => ({}),

    computed: {
        ...mapGetters([
            'activeWorlds',
            'closedWorlds',
        ]),

        worlds() {
            return [
                this.activeWorlds.sort(cbSortWorldsByStarted),
                this.closedWorlds.sort(cbSortWorldsByStarted),
            ];
        },
    },

    mounted() {
        this.$store.dispatch(WORLDS_LIST, { force: true });
    },

    methods: {
        getLink(world) {
            return world.time_of_updated_stat ? '#' : null;
        },

        getTheme(ts) {
            if (!ts) return null;

            return 'info';
        },
    },
};
</script>

<template>
    <div class="container pb-2">
        <PageHeader title="Статистика игровых миров"/>

        <div v-for="(group, index) in worlds" v-if="worlds.length">
            <h3 v-if="index === 1 && group.length">Завершенные миры</h3>

            <div class="row">
                <div class="col-lg-6 d-flex mb-1.25" v-for="w in group" v-if="w.statistic">
                    <InfoBox class="world-card" :theme="getTheme(w.time_of_updated_stat)" :link="getLink(w)">
                        <div class="world-card__info" slot="info">
                            <div class="world-card__sign">{{w.sign}}</div>
                            <SvgIcon
                                viewBox="0 0 512 341.4"
                                class="world-card__flag"
                                size="30"
                                :height="30*341.4/512"
                                :name="w.flag"
                            />
                        </div>
                        <div class="world-card__content" slot="default">
                            <div class="world-card__title">{{w.title}}</div>
                            <div class="world-card__desc">{{w.desc}}</div>
                        </div>
                        <div class="world-card__footer" slot="footer">
                            <div v-if="w.fmtUpdatedStat">Updated at: {{w.fmtUpdatedStat}}</div>
                            <div v-else>No data</div>
                        </div>
                    </InfoBox>
                </div>
            </div>
        </div>
    </div>
</template>
