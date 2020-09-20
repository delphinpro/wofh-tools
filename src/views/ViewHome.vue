<!--
  WofhTools
  View: views/ViewHome.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapActions, mapGetters } from 'vuex';
import { mdiCheck, mdiClose, mdiEmoticonSad } from '@quasar/extras/mdi-v5';
import { cbSortWorldsByStarted } from '@/utils';

export default {
  name: 'ViewHome',

  data: () => ({
    mdiCheck,
    mdiClose,
    mdiEmoticonSad,
  }),

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
  <q-page padding>
    <!--    <PageHeader title="Действующие миры Путей Истории"/>-->
    <div v-if="worlds.length">
      <q-markup-table class="table" dense>
        <caption>Действующие миры Путей истории</caption>
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
            <td :class="world.canReg?'text-green':'text-red'" class="text-center">
              <q-icon :name="world.canReg ? mdiCheck : mdiClose" size="2em"/>
            </td>
            <td class="text-right">{{ world.localStartedAt }}</td>
            <td class="text-right">{{ world.localAge }}</td>
            <td class="text-right">
              <a href="#" v-if="world.localStatUpdatedAt">{{ world.localStatUpdatedAt }}</a>
              <div v-else>Отсутствует
                <q-icon :name="mdiEmoticonSad" size="1.5em"/>
              </div>
            </td>
          </tr>
        </tbody>
      </q-markup-table>
    </div>
    <q-banner v-else inline-actions rounded class="bg-orange-7 text-white">
      Нет данных о действующих мирах
    </q-banner>
  </q-page>
</template>
