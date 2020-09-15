<!--
  WofhTools
  View: HomeView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapActions, mapGetters } from 'vuex';
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
        if (!this.activeWorlds) return [];
        return this.activeWorlds.sort(cbSortWorldsByStarted);
      },
    },
  },

  mounted() {
    this.updateWorlds();
  },

  methods: {
    ...mapActions([
      'updateWorlds',
    ]),
  },
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
            <img
              :src="'/assets/images/icons/'+world.serverCountryFlag+'.svg'"
              :alt="world.serverCountryFlag"
              :height="20*341.4/512"
              width="20"
            >
          </td>
          <td>{{ world.uSign }}</td>
          <td>{{ world.title }}</td>
          <td :class="world['can_reg']?'text-green':'text-red'" class="text-center">
            <FaIcon :name="world['can_reg']?'c.check':'times'"/>
          </td>
          <td class="text-right">{{ world.localStartedAt }}</td>
          <td class="text-right">{{ world.localAge }}</td>
          <td class="text-right">
            <a href="#" v-if="world.localStatUpdatedAt">{{ world.localStatUpdatedAt }}</a>
            <div v-else>Отсутствует
              <FaIcon name="times"/>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <Alert title="Нет данных о действующих мирах" v-else></Alert>
  </div>

</template>
