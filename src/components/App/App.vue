<!--
  WofhTools
  Component: App.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>

import { mapGetters } from 'vuex';
import AppNavbar from '@/components/App/AppNavbar';
import AppFooter from '@/components/App/AppFooter';
import Loading from '@/components/Widgets/Loading';

export default {
  name: 'App',

  components: {
    AppNavbar,
    AppFooter,
    Loading,
  },

  data: () => ({
    left : false,
    right: false,
  }),

  computed: {
    ...mapGetters([
      'loading',
    ]),
    lsEnabled() {
      return this.$route.meta.left;
    },
    rsEnabled() {
      return !!this.$route.matched[this.$route.matched.length - 1].components.right;
    },
  },
};
</script>

<template>
  <q-layout view="hHh LpR lff" id="app">

    <q-header reveal elevated class="bg-primary text-white">
      <AppNavbar
        :ls-left="left"
        @toggle-left-drawer="left=!left"
      />
    </q-header>

    <q-drawer v-model="left" side="left" overlay bordered v-if="lsEnabled">
    </q-drawer>

    <q-drawer v-model="right" side="right" v-if="rsEnabled">
      <router-view name="right"/>
    </q-drawer>

    <q-page-container>
      <router-view/>
    </q-page-container>

    <AppFooter/>

  </q-layout>
</template>
