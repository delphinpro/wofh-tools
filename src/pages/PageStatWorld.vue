<!--
  WofhTools
  Page: PageStatWorld.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import Vue from 'vue';
import { mapActions, mapGetters } from 'vuex';
import InfoCard from '@/components/InfoCard.vue';
import { farClock, fasUsers, fasUserCheck, fasFlag } from '@quasar/extras/fontawesome-v5';
import PageHeader from '@/components/App/PageHeader';

export default {
  name: 'StatWorldView',

  components: {
    PageHeader,
    InfoCard,
  },

  data: () => ({
    farClock,
    fasUsers,
    fasUserCheck,
    fasFlag,

    stat     : null,
    countries: [],
  }),

  computed: {
    ...mapGetters([
      'worldBySign',
    ]),

    pageTitle() { return this.currentWorld ? `Статистика ${this.currentWorld.uSign}` : ''; },
    worldAge() { return this.currentWorld ? this.currentWorld.age : ''; },

    currentWorld() { return this.worldBySign(this.$route.params['sign']); },

    statSkeleton() { return !this.stat; },
    accountsTotal() { return this.stat ? this.stat.accountsTotal : null; },
    accountsActive() { return this.stat ? this.stat.accountsActive : null; },
    countriesTotal() { return this.stat ? this.stat.countriesTotal : null; },
  },

  mounted() {
    this.loadData();
  },

  methods: {
    ...mapActions([
      'updateWorlds',
    ]),

    async loadData() {
      await this.updateWorlds();
      this.stat = await this.getCommonStat(this.$route.params['sign']);
    },

    getCommonStat(sign) {
      return Vue.axios.get(`/stat/${sign}/last`).catch(e => {
        console.log(e);
      });
    },
  },
};
</script>

<template>
  <q-page padding>
    <PageHeader :title="pageTitle"/>

    <h2>Общая статистика</h2>
    <div class="row q-col-gutter-md">
      <div class="col-grow c-ol-sm-6 col-md-3 d-flex" v-if="worldAge">
        <InfoCard class="bg-info text-white"
          :skeleton="statSkeleton"
          :icon="farClock"
          :title="worldAge"
          text="дней длится раунд"
        />
      </div>
      <div class="col-grow c-ol-sm-6 col-md-3">
        <InfoCard class="bg-warning text-white"
          :skeleton="statSkeleton"
          :icon="fasUsers"
          :title="accountsTotal"
          text="регистраций"
        />
      </div>
      <div class="col-grow c-ol-sm-6 col-md-3">
        <InfoCard class="bg-positive text-white"
          :skeleton="statSkeleton"
          :icon="fasUserCheck"
          :title="accountsActive"
          text="активных игроков"
        />
      </div>
      <div class="col-grow c-ol-sm-6 col-md-3">
        <InfoCard class="bg-negative text-white"
          :skeleton="statSkeleton"
          :icon="fasFlag"
          :title="countriesTotal"
          text="стран"
        />
      </div>
    </div>

    <q-btn to="/stat/ru45/countries">Countries</q-btn>
    <q-btn to="/stat/ru45/players">Players</q-btn>
    <q-btn to="/stat/ru45/towns">Towns</q-btn>
  </q-page>
</template>
