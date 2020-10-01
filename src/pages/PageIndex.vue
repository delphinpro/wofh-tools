<!--
  WofhTools
  Page: PageIndex.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mdiCheck, mdiClose, mdiEmoticonSad } from '@quasar/extras/mdi-v5';
import { mapGetters } from 'vuex';
import LangFlag from '@/components/Elements/LangFlag';
import { ROUTE_STAT_WORLD } from '@/constants';

export default {
  name: 'PageIndex',

  components: {
    LangFlag,
  },

  data: () => ({
    mdiCheck,
    mdiClose,
    mdiEmoticonSad,

    ROUTE_STAT_WORLD,
  }),

  computed: {
    ...mapGetters([
      'activeWorldsSortByStarted',
    ]),

    worlds: {
      get() {
        if (!this.activeWorldsSortByStarted) return [];
        return this.activeWorldsSortByStarted;
      },
    },
  },
};
</script>

<template>
  <q-page padding>
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
            <td style="width: 20px; padding-right: 0; vertical-align:middle;">
              <LangFlag :flag="world.serverCountryFlag"/>
            </td>
            <td>{{ world.uSign }}</td>
            <td>{{ world.title }}</td>
            <td :class="world.canReg?'text-green':'text-red'" class="text-center">
              <q-icon :name="world.canReg ? mdiCheck : mdiClose" size="2em"/>
            </td>
            <td class="text-right">{{ world.localStartedAt }}</td>
            <td class="text-right">{{ world.localAge }}</td>
            <td class="text-right">
              <router-link :to="{name:ROUTE_STAT_WORLD, params:{sign:world.sign}}" v-if="world.localStatUpdatedAt">
                {{ world.localStatUpdatedAt }}
              </router-link>
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
