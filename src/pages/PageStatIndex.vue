<!--
  WofhTools
  Page: PageStatIndex.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapGetters } from 'vuex';
import WorldCard from '@/components/WorldCard.vue';
import PageHeader from '@/components/App/PageHeader';

export default {
  name: 'PageStatIndex',

  components: {
    PageHeader,
    WorldCard,
  },

  data: () => ({}),

  computed: {
    ...mapGetters([
      'activeWorldsSortByStarted',
      'closedWorldsSortByStarted',
    ]),

    worlds() {
      return [
        this.activeWorldsSortByStarted,
        this.closedWorldsSortByStarted,
      ];
    },
  },

  mounted() {
    this.$store.dispatch('updateWorlds', { force: true });
  },
};
</script>

<template>
  <q-page padding>
    <PageHeader title="Статистика игровых миров"/>

    <div class="q-mb-xl"
      v-for="(group, index) in worlds"
      v-if="worlds.length"
    >
      <h3 v-if="index === 1 && group.length">Завершенные миры</h3>
      <div class="row q-col-gutter-lg">
        <div class="col-12 col-sm-6" v-for="w in group" v-if="w.statistic">
          <WorldCard :world="w"/>
        </div>
      </div>
    </div>

  </q-page>
</template>
