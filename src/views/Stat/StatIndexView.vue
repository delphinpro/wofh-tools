<!--
  WofhTools
  File: StatIndexView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapActions, mapGetters } from 'vuex';
import WorldCard from '@/components/Widgets/WorldCard.vue';
import { cbSortWorldsByStarted } from '@/utils';

export default {
  name: 'StatIndexView',

  components: {
    WorldCard,
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
    this.updateWorlds({ force: true });
  },

  methods: {
    ...mapActions([
      'updateWorlds',
    ]),
  },
};
</script>

<template>
  <QPage padding>
    <PageHeader title="Статистика игровых миров"/>

    <div class="q-mt-md"
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

  </QPage>
</template>
