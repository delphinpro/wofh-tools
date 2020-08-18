<!--
  WofhTools
  File: StatHomeView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapActions, mapGetters } from 'vuex';
import { cbSortWorldsByStarted } from '@/utils';
import InfoBox from '@/components/Widgets/InfoBox';


export default {
  name: 'StatHomeView',

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
    // this.$store.dispatch(WORLDS_LIST, { force: true });
    this.updateWorlds({ force: true });
  },

  methods: {
    ...mapActions([
      'updateWorlds',
    ]),
    getLink(world) {
      if (!world.fmtUpdatedStat) return null;
      return {
        name: 'statWorld',
        params: {
          sign: world.sign,
        },
      };
    },

    getTheme(ts) {
      if (!ts) return null;

      return 'info';
    },
  },
};
</script>

<template>
  <div>
    <PageHeader title="Статистика игровых миров"/>

    <div v-for="(group, index) in worlds" v-if="worlds.length">
      <h3 v-if="index === 1 && group.length">Завершенные миры</h3>

      <div class="row">
        <div class="col-lg-6 d-flex mb-1.25" v-for="w in group" v-if="w.statistic">
          <InfoBox :link="getLink(w)" :theme="getTheme(w.fmtUpdatedStat)" class="world-card">
            <div class="world-card__info" slot="info">
              <div class="world-card__sign">{{ w.sign }}</div>
              <img
                class="world-card__flag"
                :src="'/assets/images/icons/'+w.flag+'.svg'"
                :alt="w.flag"
                :height="30*341.4/512"
              >
            </div>
            <div class="world-card__content" slot="default">
              <div class="world-card__title">{{ w.title }}</div>
              <div class="world-card__desc">{{ w.desc }}</div>
            </div>
            <div class="world-card__footer" slot="footer">
              <div v-if="w.fmtUpdatedStat">Updated at: {{ w.fmtUpdatedStat }}</div>
              <div v-else>No data</div>
            </div>
          </InfoBox>
        </div>
      </div>
    </div>
    <!--    <pre>{{ worlds }}</pre>-->
  </div>
</template>
